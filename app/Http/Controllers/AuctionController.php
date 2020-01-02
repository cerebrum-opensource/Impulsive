<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;
use URL;
use Log;
use Auth;
use DateTime;
use App\User;
use App\Models\{Auction,
    Product,
    Attribute,
    AuctionQueue,
    UserBidHistory,
    UserBid,
    AuctionWinner,
    AuctionAutomateAgent,
    UserWishlist,
    UserBonusBid,
    Setting,
    UserLossAuction,
    ProductAmountCategory,
    ProductAfterplayCategory};
use Carbon\Carbon;
use App\Http\Requests\User\BidAgentRequest;
use App\Mail\AuctionRecall;
use App\Mail\BidWon;
use App\Mail\BreakTime;
use App\Mail\SendRecallUserEmail;
use App\Mail\SendEmailToAuctionLossers;
use DB;
use Config;
use Mail;

class AuctionController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->timezone = user_time_zone();

    }

    public function index(Request $request)
    {
        //  echo $request->get('auctionsId');
        //print_r(json_decode($request->get('auctionsId'),true));

        //return view('paywithpaypal');
    }


    /**
     * used to get the list of the auctions and its value on every second for live auctions
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response : Json
     */

    public function postAuctions(Request $request)
    {
        $setting = Setting::first();

        $auctionsId = [];
        if ($request->get('auctionsId') && !empty($request->get('auctionsId'))) {
            foreach ($request->get('auctionsId') as $key => $value) {
                $auctionsId[] = encrypt_decrypt('decrypt', $value);
            }
        }


        $auctions = AuctionQueue::with('bidHistory')->whereIn('id', $auctionsId)->get();
        if ($request->has('detail_id') && $request->get('detail_id') != 0) {
            $auctions = AuctionQueue::with('bidHistory')->whereIn('id', $auctionsId)->where('id', '!=', $request->get('detail_id'))->get();
        }

        $timezone = $this->timezone;

        if ($request->has('detail_id') && $request->get('detail_id') != 0) {
            $auctionsId[] = $request->get('detail_id');
        }
        $liveAuctions = AuctionQueue::with('product', 'auction', 'bidHistory')->whereNotIn('id', $auctionsId)->where('status', AuctionQueue::ACTIVE)->get();
        if ($request->has('is_watchlist') && $request->get('is_watchlist')) {
            $liveAuctions = AuctionQueue::with('product', 'auction', 'bidHistory', 'wishList')->whereNotIn('id', $auctionsId)->where('status', AuctionQueue::ACTIVE)->get();
        }

        // if any new auction is create/ or any planned or queue auction move to live then append html
        if (count($liveAuctions)) {
            if ($request->has('is_watchlist') && $request->get('is_watchlist') == 1) {
                $data['latest_live_auctions'] = view('user.auctions.overview.live_auctions', [
                    'liveAuctions' => $liveAuctions,
                    'timezone'     => $timezone,
                ])->render();
            } elseif ($request->has('is_watchlist') && $request->get('is_watchlist') == 0) {
                $data['latest_live_auctions'] = view('user.auctions.latest_live_auctions', [
                    'liveAuctions' => $liveAuctions,
                    'timezone'     => $timezone,
                ])->render();
            }

        }

        $res     = '';
        $name    = '';
        $user_id = '';

        foreach ($auctions as $key => $auction) {
            $nowDate        = \Carbon\Carbon::now()->timezone($timezone)->format('Y-m-d H:i:s');
            $nowDateGermany = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d H:i:s');

            $auctionStartTime = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d') . ' ' . $setting->break_start_time;
            $auctionEndTime   = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d') . ' ' . $setting->break_end_time;

            $break = auction_time_helper($auctionStartTime, $auctionEndTime);

            if ($break) {
                $diffGermany = 1;
            } else {
                $diffGermany = 0;
            }

            $diffGermanyEnd = strtotime($auctionEndTime) - strtotime($nowDateGermany);


            $breakTime = date("g:i", strtotime($auctionStartTime));

            $countDownStart = 0;
            $automaticBid   = 0;
            $status         = 1;

            // Send the last bidder id and user name in response.
            if (count($auction->bidHistory)) {
                $userData = $auction->bidHistory[0]->user;
                $name     = $userData->username;
                $user_id  = $userData->id;
            } else {
                $name    = '';
                $user_id = 0;
            }

            if (!Auth::guest()) {
                $userID               = Auth::id();
                $auctionAutomateAgent = AuctionAutomateAgent::where('user_id', $userID)->where('auction_queue_id', $auction->id)->where('status', AuctionAutomateAgent::ACTIVE)->where('remaining_bid', '>', 0)->count();

                if ($auctionAutomateAgent) {
                    $automaticBid = 1;
                }
            }

            // check is the bid time is over or not
            if ($auction->duration_count > 0) {

                if ($auction->duration_count > $diffGermanyEnd)
                    $timerTime = $auction->duration_count;
                else
                    $timerTime = $auction->duration_count;

//                $timeStamp = seconds_to_h_m_s($timerTime);


                //todo: Mathias Hack
//                if ($timerTime != 0) {
//                    $timeStamp = seconds_to_h_m_s($timerTime);
//                } else {
//                    $timeStamp = seconds_to_h_m_s($timerTime);
//                }

                $timeStamp = $this->timeStampHack($timerTime);

            } else {
                // if bid time is over complete the bid and assign the winner to it.
                $timerTime = '';
                $timeStamp = 'Check';
                $status    = 0;
            }


            if ($auction->duration_count > $auction->final_countdown) {

            } else {
                $countDownStart = 1;
            }

            $breakTimeHtml = trans('label.counitnue_at') . ' ' . $breakTime . ' ' . trans('label.clock');

            //  $priceFormat = price_reflect_format($auction->bid_price).' '.CURRENCY_ICON.' (' .$auction->bid_count.')';
            $priceFormat = price_reflect_format($auction->bid_price) . ' ' . CURRENCY_ICON;
            // response to frontend to parse using ajax and jquery
            $res .= encrypt_decrypt('encrypt', $auction->id) . '|' . $status . '|' . $timeStamp . '|' . $auction->bid_count . '|' . $priceFormat . '|' . $auction->final_countdown_start_time . '|' . $name . '|' . $user_id . '|' . $countDownStart . '|' . $automaticBid . '|' . $nowDateGermany . '|' . $diffGermany . '|' . $diffGermanyEnd . '|' . $breakTimeHtml . '|' . $timerTime;
            if ($key + 1 < count($auctions)) {
                $res .= ';';
            }


        }
        $data['data'] = $res;
        return response()->json([
                                    'message' => '',
                                    'html'    => $data,
                                ], 200);
    }


    /**
     * function to bid on auction
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response : 0 or 1
     */

    public function bidOnAuction(Request $request)
    {
        $bidd = new UserBonusBid();

        if ($request->has('id')) {
            $auctionId = encrypt_decrypt('decrypt', $request->get('id'));
            $auc       = AuctionQueue::find($auctionId);
            if (!$auc) {
                return response()->json(['isLoggedIn' => ''], 403);
            }
            if (!$auc->status) {
                return response()->json(['message' => '5',], 200);
                // return '5';
            }
        }
        if ($request->has('user_id')) {
            $user_id = encrypt_decrypt('decrypt', $request->get('user_id'));
            $user    = User::find($user_id);
            if (!$user) {
                return response()->json(['isLoggedIn' => ''], 403);
            }
        }

        //  $last_row = UserBidHistory::where('auction_id',$auctionId)->latest()->first();

        $last_row = UserBidHistory::where('auction_id', $auctionId)->orderBy('id', 'DESC')->first();

        $auctionAutomateAgent = AuctionAutomateAgent::where('user_id', $user_id)->where('auction_queue_id', $auc->id)->where('status', AuctionAutomateAgent::ACTIVE)->where('remaining_bid', '>', 0)->count();

        // if same user id bid again or handle the double click in frontend or automatic bid is setup for the same auction

        $setting = Setting::first();
        // is there is setting record used that else value from constant file is used
        if ($setting) {
            $per_bid_price_raise     = $setting->per_bid_price_raise;
            $per_bid_count_raise     = $setting->per_bid_count_raise;
            $per_bid_count_descrease = $setting->per_bid_count_descrease;
        } else {
            $per_bid_price_raise     = PER_BID_PRICE_RAISE;
            $per_bid_count_raise     = PER_BID_COUNT_RAISE;
            $per_bid_count_descrease = PER_BID_COUNT_DECREASE;
        }

        if (file_exists(__DIR__ . '/bid-lock')) {
            $microtime = file_get_contents(__DIR__ . '/bid-lock');
        } else {
            $microtime = 0;
        }

        if ((float)$microtime > (microtime(true) - 1)) {
            return response()->json(['message' => '6',], 200);
        } else {
            file_put_contents(__DIR__ . '/bid-lock', microtime(true));
        }

        if ($auc->duration_count < $auc->final_countdown) {
            $auctionData['duration_count'] = $auc->final_countdown;
            AuctionQueue::where('id', $auctionId)->update($auctionData);
        }

        if (($last_row && $last_row->user_id == $user_id) || $auctionAutomateAgent && $auctionAutomateAgent != 0) {

            if ($last_row && $last_row->user_id == $user_id) {
                $user_free_bid = UserBid::where('user_id', $user_id)->first();
                //return $user_free_bid;
                return response()->json([
                                            'message' => '4',
                                            'data'    => $user_free_bid,
                                        ], 200);
                //return '4';
            }
            return response()->json(['message' => '3',], 200);
            // return '3';
        } else {

            $timezone = $this->timezone;

            DB::beginTransaction();
            $data['user_id']    = encrypt_decrypt('decrypt', $request->get('user_id'));
            $data['auction_id'] = encrypt_decrypt('decrypt', $request->get('id'));
            $data['bid_count']  = $auc->bid_count ? $auc->bid_count + $per_bid_count_raise : $per_bid_count_raise;
            $data['bid_price']  = $auc->bid_price ? $auc->bid_price + $per_bid_price_raise : $per_bid_price_raise;
            $data['bid_date']   = \Carbon\Carbon::now()->timezone(Config::get('app.timezone'))->format('Y-m-d');
            $save               = UserBidHistory::create($data);

            if ($save) {
                $auctionData['bid_count'] = $auc->bid_count ? $auc->bid_count + $per_bid_count_raise : $per_bid_count_raise;
                $auctionData['bid_price'] = $auc->bid_price ? $auc->bid_price + $per_bid_price_raise : $per_bid_price_raise;
                if ($request->has('countdown') && $request->get('countdown')) {
                    $pendingSec = $auc->final_countdown - $auc->duration_count;
                    //  $auctionData['duration_count'] = $auc->duration_count+$pendingSec;
                }

                $update = AuctionQueue::where('id', $auctionId)->update($auctionData);

                if (UserBid::where('user_id', $user_id)->count()) {
                    UserBid::where('user_id', $user_id)->decrement('bid_count', $per_bid_count_descrease);
                }
                $bonusBid = $bidd->updateBonusBid($user_id, $per_bid_count_descrease);


                if ($update && $bonusBid) {
                    DB::commit();
                    $user_free_bid = UserBid::where('user_id', $data['user_id'])->first();
                    // return $user_free_bid;
                    return response()->json([
                                                'message' => '1',
                                                'data'    => $user_free_bid,
                                            ], 200);
                    //return '1';
                }
            }
            DB::rollBack();
            return '0';
        }
    }

    /**
     * function to get the auction detail
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\View
     */

    public function getAuctionDetail($auctionId, Request $request)
    {

        $timezone             = $this->timezone;
        $auctionAutomateAgent = [];
        $pendingBidCount      = '';
        $id                   = encrypt_decrypt('decrypt', $auctionId);
        $auction              = AuctionQueue::with('product')->findOrfail($id);
        $bidHistory           = UserBidHistory::with('user')->where('auction_id', $id)->orderBy('id', 'desc')->take(PAGINATION_COUNT_10)->get();
        $bidAgentSetup        = false;

        $setting        = Setting::first();
        $nowDateGermany = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d H:i:s');

        $auctionStartTime = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d') . ' ' . $setting->break_start_time;
        $auctionEndTime   = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d') . ' ' . $setting->break_end_time;

        $break = auction_time_helper($auctionStartTime, $auctionEndTime);

        if ($break) {
            $diffGermany = 1;
        } else {
            $diffGermany = 0;
        }


        $diffGermanyEnd = strtotime($auctionEndTime) - strtotime($nowDateGermany);
        // logic to check the final coundown need to start or not


        $breakTime = date("g:i", strtotime($auctionStartTime));


        // get the remainning bid for user if bid agent is setup
        if (!Auth::guest()) {
            $userID               = Auth::id();
            $auctionAutomateAgent = AuctionAutomateAgent::where('user_id', $userID)->where('auction_queue_id', $id)->get();
            if (count($auctionAutomateAgent) > 0) {
                $pendingBidCount = $auctionAutomateAgent['0']->remaining_bid;
                $bidAgentSetup   = true;
            }
        }

        $attribute_value = [];
        $fields          = [];
        $product         = Product::with('category_name', 'product_attributes')->find($auction->product->id);
        if (count($product->product_attributes)) {
            $attribute_value = json_decode($product->product_attributes[0]->product_attributes, true);
            $attribute_ids   = array_keys($attribute_value);
            $fieldValues     = $attribute_value;
            $fields          = Attribute::whereIn('id', $attribute_ids)->get();

        }
        $liveAuctions    = AuctionQueue::with('product', 'auction', 'bidHistory')->where('id', '!=', $id)->where('status', AuctionQueue::ACTIVE)->get();
        $plannedAuctions = Auction::with('product')->where('auction_type', Auction::PLANNED)->where('status', Auction::ACTIVE)->get();

        if ($request->ajax()) {
            $nowDate = \Carbon\Carbon::now()->timezone($timezone)->format('Y-m-d H:i:s');
            // $diff = strtotime($auction->end_time) - strtotime($nowDate);
            // check is the bid time is over or not
            if ($auction->duration_count > 0) {

                if ($auction->duration_count > $diffGermanyEnd)
                    $timerTime = $auction->duration_count;
                else
                    $timerTime = $auction->duration_count;

                // todo:Mathias HACK
//                $timeStamp = seconds_to_h_m_s($timerTime);


                $timeStamp = $this->timeStampHack($timerTime);

            } else {
                $timeStamp = 'Check';
                //$timeStamp = '00:00:00';
            }

            $breakTime = date("g:i", strtotime($auctionStartTime));

            $breakTimeHtml = trans('label.counitnue_at') . ' ' . $breakTime . ' ' . trans('label.clock');


            $username = trans('label.no_bidder');
            if (count($auction->bidHistory))
                $username = $auction->bidHistory[0]->user->username;

            $data['bid_history'] = view('user.auctions.bid_history', ['bidHistory' => $bidHistory])->render();
            $data['count_down']  = $timeStamp;
            $data['breaktime']   = $diffGermany;
            //$data['bid_price'] = CURRENCY_ICON.' '.price_reflect_format($auction->bid_price);
            //  $data['bid_price'] = price_reflect_format($auction->bid_price).' '.CURRENCY_ICON.'('.$auction->bid_count.')';
            $data['bid_price']         = price_reflect_format($auction->bid_price) . ' ' . CURRENCY_ICON;
            $data['bid_latest_user']   = $username;
            $data['pending_bid_count'] = $pendingBidCount;
            $data['breakTimeHtml']     = $breakTimeHtml;

            return response()->json([
                                        'message' => '',
                                        'html'    => $data,
                                    ], 200);
        } else {
            return view('user.auctions.auction_detail', compact('auction', 'bidHistory', 'liveAuctions', 'plannedAuctions', 'timezone', 'attribute_value', 'fields', 'auctionAutomateAgent', 'pendingBidCount', 'bidAgentSetup'));
        }

    }


    // method is used to get the list of the auctions and its value on every second for live auctions

    public function postAuctionsHtml(Request $request)
    {
        $timezone     = $this->timezone;
        $liveAuctions = AuctionQueue::with('product', 'auction', 'bidHistory')->where('status', AuctionQueue::ACTIVE)->get();
        return view('user.auctions.live_auctions', [
            'liveAuctions' => $liveAuctions,
            'timezone'     => $timezone,
        ])->render();
    }


    /**
     * function to get the user activity
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response : Json
     */

    public function getUserActivityDetail(Request $request)
    {
        if ($request->has('user_id') && $request->get('user_id') != '') {
            $user = User::find(encrypt_decrypt('decrypt', $request->get('user_id')));
            if (!$user) {
                return response()->json([
                                            'status'  => '0',
                                            'message' => trans('message.user_is_not_valid'),
                                        ], 200);
            }
            $activity = $user->getUserActivity();
            return response()->json([
                                        'status'   => '1',
                                        'data'     => $user,
                                        'activity' => $activity,
                                    ], 200);

        } else {
            return response()->json([
                                        'status'  => '0',
                                        'message' => trans('message.user_is_not_valid'),
                                    ], 403);
        }
    }


    /**
     * function to setup the bid agent for a user and update the count if already bid agent for user for particular auction
     * @param BidAgentRequest $request
     *
     * @return \Illuminate\Http\Response :: json
     */


    public function postSetupBidAgent(BidAgentRequest $request)
    {

        if ($request->has('id')) {
            $auctionData = AuctionQueue::find(encrypt_decrypt('decrypt', $request->get('id')));

            $type = $request->get('start_type');

            if ($type == IMMEDIATLY) {
                $data['status'] = AuctionAutomateAgent::ACTIVE;
            } else {
                $data['status'] = AuctionAutomateAgent::NOT_ACTIVE;
            }

            if ($type == FINAL_COUNTDOWN) {
                //$data['start_time'] = $auctionData->final_time_utc;
                $data['duration_count'] = $auctionData->final_countdown;
            } elseif ($type == FOUR_HOURS) {
                //  $data['start_time'] = date('Y-m-d H:i:s',strtotime('-4 hour',strtotime($auctionData->end_time_utc)));
                $data['duration_count'] = 14400;
            } elseif ($type == FIVE_MINUTES) {
                //$data['start_time'] = date('Y-m-d H:i:s',strtotime('-5 minutes',strtotime($auctionData->end_time_utc)));
                $data['duration_count'] = 300;
            }

            $data['auction_queue_id'] = $auctionData->id;
            $data['type']             = $type;
            $data['auction_id']       = $auctionData->auction_id;
            $data['user_id']          = encrypt_decrypt('decrypt', $request->get('user_id'));
            $data['remaining_bid']    = $request->get('bidCount');
            $data['total_bids']       = $request->get('bidCount');
            $time = microtime(true) + rand(2, $auctionData->final_countdown);
            $datetime = new DateTime();
            $datetime->setTimestamp($time);
            $start_time = $datetime->format('Y-m-d H:i:s.u');
            $data['start_time']       = $start_time;
            //print_r($data);
            //die();
            $update = AuctionAutomateAgent::where('auction_id', $auctionData->auction_id)->where('auction_queue_id', $auctionData->id)
                ->where('user_id', encrypt_decrypt('decrypt', $request->get('user_id')))->get();

            if (count($update)) {
                $response = AuctionAutomateAgent::find($update[0]->id)->update(['remaining_bid' => DB::raw('remaining_bid + ' . $request->get('bidCount')),
                   'total_bids'    => DB::raw('total_bids + ' . $request->get('bidCount')),
                   'status'        => $type == IMMEDIATLY ? AuctionAutomateAgent::ACTIVE : AuctionAutomateAgent::NOT_ACTIVE,
                   'start_time'    => $start_time,]);
            } else {
                $response = AuctionAutomateAgent::create($data);
            }

            if ($response) {

                $bidd      = new UserBonusBid();
                $countBidd = $bidd->updateBonusBidOnAgent($data['user_id'], $request->get('bidCount'));
                $free_bid  = UserBid::where('user_id', $data['user_id'])->first();
                if ($countBidd > 0) {
                    UserBid::where('user_id', $data['user_id'])->decrement('bid_count', $countBidd);


                    if ($free_bid && $free_bid->free_bid) {
                        if ($free_bid->free_bid < $countBidd) {
                            UserBid::where('user_id', $data['user_id'])->update(['free_bid' => 0]);
                        } else {
                            UserBid::where('user_id', $data['user_id'])->decrement('free_bid', $countBidd);
                        }
                        //  UserBid::where('user_id',$data['user_id'])->decrement('free_bid', $countBidd);

                    }
                }


                $user_free_bid = UserBid::where('user_id', $data['user_id'])->first();
                return response()->json([
                                            'message' => trans('message.bid_agent_setup_successfully'),
                                            'status'  => $response,
                                            'data'    => $user_free_bid,
                                        ], 200);
            } else {
                return response()->json([
                                            'message' => trans('message.bid_agent_cannot_setup'),
                                            'status'  => $response,
                                        ], 200);
            }

        }

    }


    // it is cron job that is run using wget dont touch it

    public function runBidAgent()
    {

        $setting         = Setting::first();
        $runningAuctions = AuctionQueue::where('status', AuctionQueue::ACTIVE)->get();

        foreach ($runningAuctions as $key => $runningAuction) {

            $auctionStartTime = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d') . ' ' . $setting->break_start_time;
            $auctionEndTime   = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d') . ' ' . $setting->break_end_time;

            $break = auction_time_helper($auctionStartTime, $auctionEndTime);

            if ($break) {
                continue;
            }

            if ($runningAuction->duration_count < 14400) {

                $auction = AuctionAutomateAgent::where('auction_queue_id', $runningAuction->id)->where('type', FOUR_HOURS)
                    ->update(['status' => AuctionAutomateAgent::ACTIVE]);
            }

            if ($runningAuction->duration_count < 300) {

                $auction = AuctionAutomateAgent::where('auction_queue_id', $runningAuction->id)->where('type', FIVE_MINUTES)
                    ->update(['status' => AuctionAutomateAgent::ACTIVE]);
            }

            $bidAgents = AuctionAutomateAgent::where('status', AuctionAutomateAgent::ACTIVE)->where('auction_id', $runningAuction->auction_id)->where('remaining_bid', '>', 0)->orderBy('id', 'ASC')->get();
            $lastBids  = UserBidHistory::where('auction_id', $runningAuction->id)->orderBy('id', 'DESC')->get();
            $lastBid   = $lastBids->first();
            $lastId    = false;

            foreach ($lastBids as $bid) {

                if (!$lastId && $bid->is_manual == 0) {
                    $lastId = $bid->user_id;
                }
            }

            $next   = false;
            $nextId = false;

            foreach ($bidAgents as $a) {

                if ($next && !$nextId) {
                    $nextId = $a->user_id;
                }

                if ($a->user_id == $lastId) {
                    $next = true;
                }
            }

            if (!$nextId && $bidAgents->first()) {
                $nextId = $bidAgents->first()->user_id;
            }

            foreach ($bidAgents as $agent) {

                if ($agent->user_id == $nextId && $agent->start_time && $agent->start_time <= microtime(true) && $lastBid && $agent->user_id != $lastBid->user_id || $nextId && !$lastBid) {

                    AuctionAutomateAgent::find($agent->id)->update([
                                                                       'remaining_bid' => DB::raw('remaining_bid - ' . $setting->per_bid_count_descrease),
                                                                       'start_time'    => (microtime(true) + rand(2, $runningAuction->final_countdown)),
                                                                   ]);

                    UserBidHistory::create([
                                               'user_id'    => $agent->user_id,
                                               'auction_id' => $runningAuction->id,
                                               'bid_count'  => ($lastBid ? ($lastBid->bid_count + $setting->per_bid_count_raise) : $setting->per_bid_count_raise),
                                               'bid_price'  => ($lastBid ? ($lastBid->bid_price + $setting->per_bid_price_raise) : $setting->per_bid_price_raise),
                                               'created_at' => date('Y-m-d H:i:s', time()),
                                               'updated_at' => date('Y-m-d H:i:s', time()),
                                               'is_manual'  => 0,
                                               'bid_date'   => date('Y-m-d', time()),
                                           ]);

                    $durationCount = $runningAuction->duration_count;

                    if ($runningAuction->duration_count <= $runningAuction->final_countdown) {
                        $durationCount = $runningAuction->final_countdown;
                    }

                    AuctionQueue::find($runningAuction->id)->update([
                                                                        'bid_count'      => ($lastBid ? ($lastBid->bid_count + 1) : 1),
                                                                        'bid_price'      => ($lastBid ? ($lastBid->bid_price + 0.01) : 0.01),
                                                                        'updated_at'     => date('Y-m-d H:i:s'),
                                                                        'duration_count' => $durationCount,
                                                                    ]);

                    if ($agent->remaining_bid == 0) {
                        AuctionAutomateAgent::where('id', $agent->id)->update(['status' => AuctionAutomateAgent::NOT_ACTIVE]);
                    }
                }
            }
        }
        /*
                LOG::info('BID AGENT SETUP');
                $todayDate =  \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                $todayDatePast =  \Carbon\Carbon::now()->subSeconds(5)->format('Y-m-d H:i:s');


                $setting = Setting::first();
                $nowDateGermany = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d H:i:s');


                $auctionStartTime = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d').' '.$setting->break_start_time;
                $auctionEndTime   = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d').' '.$setting->break_end_time;

                $break = auction_time_helper($auctionStartTime,$auctionEndTime);


                if($break){

                } else {

                    $runningAuctions = AuctionQueue::where('status',AuctionQueue::ACTIVE)->get();

                    // update the countdown
                    foreach ($runningAuctions as $key => $runningAuction) {
                     //   echo $runningAuction->duration_count.'<br>';

                        if($runningAuction->duration_count < 14400 && $runningAuction->duration_count > 300){
                            $auction  = AuctionAutomateAgent::where('auction_queue_id', $runningAuction->id)->where('type', FOUR_HOURS)
                            ->update(['status'=>AuctionAutomateAgent::ACTIVE]);
                        }
                        if($runningAuction->duration_count < 300 && $runningAuction->duration_count > $runningAuction->final_countdown){
                          //  echo $runningAuction->duration_count.'<br>';
                          //  echo "300";
                            $auction  = AuctionAutomateAgent::where('auction_queue_id', $runningAuction->id)->where('type', FIVE_MINUTES)
                            ->update(['status'=>AuctionAutomateAgent::ACTIVE]);
                        }
                        if($runningAuction->duration_count <= $runningAuction->final_countdown){
                            AuctionAutomateAgent::where('auction_queue_id', $runningAuction->id)
                            ->where('type', FINAL_COUNTDOWN)
                            ->update(['status'=>AuctionAutomateAgent::ACTIVE]);
                        }
                    }

                    $bidAgents = AuctionAutomateAgent::where('status',AuctionAutomateAgent::ACTIVE)->where('remaining_bid','>=',0)->get();

                    $setting = Setting::first();
                    // is there is setting record used that else value from constant file is used

                    if($setting){
                        $per_bid_price_raise = $setting->per_bid_price_raise;
                        $per_bid_count_raise = $setting->per_bid_count_raise;
                    }
                    else {
                        $per_bid_price_raise = PER_BID_PRICE_RAISE;
                        $per_bid_count_raise = PER_BID_COUNT_RAISE;
                    }

                    foreach ($bidAgents as $key => $bidAgent) {
                        $last_row = UserBidHistory::where('auction_id',$bidAgent->auction_queue_id)->orderBy('id', 'DESC')->first();
                        $exit = 0;
                        if($last_row && $last_row->user_id == $bidAgent->user_id){
                            if($bidAgent->remaining_bid == 0){
                                AuctionAutomateAgent::where('id',$bidAgent->id)->update(['status' => AuctionAutomateAgent::NOT_ACTIVE]);
                                return;
                            }
                        }
                        else {

                            if($bidAgent->remaining_bid == 0){
                                AuctionAutomateAgent::where('id',$bidAgent->id)->update(['status' => AuctionAutomateAgent::NOT_ACTIVE]);
                                return;
                            }
                            $timezone = $this->timezone;
                            $auctionId = $bidAgent->auction_queue_id;
                            $user_id = $bidAgent->user_id;
                            $auc = AuctionQueue::find($auctionId);
                            DB::beginTransaction();

                            if($auc->status && $auc->duration_count > 0 ){
                                $data['user_id'] = $user_id;
                                $data['auction_id'] = $bidAgent->auction_queue_id;
                                $data['bid_count'] = $auc->bid_count ? $auc->bid_count + $per_bid_count_raise : $per_bid_count_raise;
                                $data['bid_price'] = $auc->bid_price ? $auc->bid_price + $per_bid_price_raise : $per_bid_price_raise;
                                $data['is_manual'] = UserBidHistory::AUTOMATE;
                                $data['bid_date'] = \Carbon\Carbon::now()->timezone(Config::get('app.timezone'))->format('Y-m-d');
                                $save = UserBidHistory::create($data);

                            }
                            else {
                                DB::rollBack();
                                $save = false;
                            }

                            if($save)
                            {
                                $exit = 1;
                                $auctionData['bid_count'] =  $auc->bid_count ? $auc->bid_count + $per_bid_count_raise : $per_bid_count_raise;
                                $auctionData['bid_price'] =  $auc->bid_price ? $auc->bid_price + $per_bid_price_raise : $per_bid_price_raise;

                                $nowDate = \Carbon\Carbon::now()->timezone($timezone)->format('Y-m-d H:i:s');
                               // $diff = strtotime($auc->end_time) - strtotime($nowDate);
                              //  $diffwithCountdown = strtotime($auc->final_countdown_start_time) - strtotime($nowDate);


                               // if($diffwithCountdown < 0 && $auc->status){

                                if($auc->duration_count < $auc->final_countdown && $auc->status ){
                                    $pendingSec = $auc->final_countdown-$auc->duration_count;
                                    $auctionData['duration_count'] = $auc->duration_count+$pendingSec;
                                }

                                $update = AuctionQueue::where('id',$auctionId)->update($auctionData);

                                if(UserBid::where('user_id',$user_id)->count()){
                                   // UserBid::where('user_id',$user_id)->decrement('bid_count', PER_BID_COUNT_RAISE);
                                }
                                if($update){
                                    if($bidAgent->remaining_bid == 0){
                                        AuctionAutomateAgent::find($bidAgent->id)->update([
                                            'remaining_bid' => DB::raw('remaining_bid - '.$per_bid_count_raise),
                                            'total_bids' => DB::raw('total_bids - '.$per_bid_count_raise),
                                            'status' => AuctionAutomateAgent::NOT_ACTIVE,
                                        ]);
                                    }
                                    else {
                                        AuctionAutomateAgent::find($bidAgent->id)->update([
                                            'remaining_bid' => DB::raw('remaining_bid - '.$per_bid_count_raise),
                                            'total_bids' => DB::raw('total_bids - '.$per_bid_count_raise),
                                        ]);
                                    }

                                    DB::commit();
                                }

                                DB::rollBack();
                            }
                            //
                        }
                        //if($exit == 1){
                          // exit();
                            sleep(1);
                      //  }

                    }
                }
                LOG::info('BID AGENT IS RUNNING');
                //  return true;
        */
    }


    public function auctionCountDown()
    {
        //code to send and save auction looser in table and send link to email


    }


    public function auctionCountDownDecrese()
    {
// die();
        $start = microtime(true);
        set_time_limit(60);
        for ($i = 0; $i < 59; ++$i) {
            $setting        = Setting::first();
            $nowDateGermany = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d H:i:s');


            $auctionStartTime = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d') . ' ' . $setting->break_start_time;
            $auctionEndTime   = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d') . ' ' . $setting->break_end_time;
            $break            = auction_time_helper($auctionStartTime, $auctionEndTime);
            if ($break) {
            } else {
                //////////////////////////////////////////////////
                //        $auction_id = AuctionQueue::join('user_bid_histories', 'user_bid_histories.auction_id', '=', 'auction_queues.id')
                //           ->where('user_bid_histories.is_manual','=','1')
                //           ->where('status','=','1')
                //           ->groupBy('auction_queues.id')
                //           ->pluck('auction_queues.id');
                // $auction_queue_id = AuctionAutomateAgent::whereIn('auction_queue_id',$auction_id)->where('status','1')->groupBy('auction_queue_id')->pluck('auction_queue_id');
                // $result = AuctionQueue::whereIn('id',$auction_queue_id)->where('status','1')->where(function($q){
                //             $q->where('duration_count','1')
                //               ->orWhere('duration_count','2');
                //             })
                //           ->update(['duration_count','10']);
                //////////////////////////////////////////////
                AuctionQueue::where('status', AuctionQueue::ACTIVE)->where('duration_count', '>', 0)->update([
                                                                                                                 'duration_count' => \DB::raw('duration_count - 1')
                                                                                                                 // decrement
                                                                                                             ]);
            }
            Log::info('AUCTION check from static function');
            time_sleep_until($start + $i + 1);

        }
    }


    /**
     * function to show the live auction
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\View
     */


    public function getLiveAuctions(Request $request)
    {

        $timezone = $this->timezone;
        //print_r($auctionsIds);
        $liveAuctions = AuctionQueue::with('product', 'auction', 'bidHistory')->where('status', AuctionQueue::ACTIVE)->where('duration_count', '>', 0)->get();
        // $liveAuctions = AuctionQueue::with('product','auction','bidHistory')->where('status',AuctionQueue::ACTIVE)->where('duration_count','>',0)->get();

        return view('user.auctions.running_auctions', compact('liveAuctions', 'timezone'));
    }

    /**
     * function to show the Comming auction
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\View
     */


    public function getComingAuctions(Request $request)
    {

        $timezone        = $this->timezone;
        $queueAuctions   = Auction::with('product')->where('auction_type', Auction::QUEUE)->where('status', Auction::ACTIVE)->orderBy('priority', 'ASC')->get();
        $plannedAuctions = Auction::with('product')->where('auction_type', Auction::PLANNED)->where('status', Auction::ACTIVE)->get();
        return view('user.auctions.coming_auctions', compact('queueAuctions', 'plannedAuctions', 'timezone'));

    }


    /**
     * function to bid on auction
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response 0 or 1
     */


    public function addToRecall(Request $request)
    {
        if ($request->has('id')) {
            $auctionId = encrypt_decrypt('decrypt', $request->get('id'));
            $auc       = Auction::find($auctionId);
            if (!$auc) {
                return response()->json([
                                            'isLoggedIn' => '',
                                            'message'    => trans('message.auction_is_not_valid'),
                                        ], 403);
            }
        }
        if ($request->has('user_id')) {
            $user_id = encrypt_decrypt('decrypt', $request->get('user_id'));
            $user    = User::find($user_id);
            if (!$user) {
                return response()->json([
                                            'isLoggedIn' => '',
                                            'message'    => trans('message.user_is_not_valid'),
                                        ], 403);
            }
        }

        if ($request->has('type')) {
            $typeArray[] = UserWishlist::RECALL;
            $typeArray[] = UserWishlist::WISH_LIST;
            $type        = encrypt_decrypt('decrypt', $request->get('type'));
            //echo $type;
            if (!in_array($type, $typeArray)) {
                return response()->json([
                                            'isLoggedIn' => '',
                                            'message'    => trans('message.type_is_not_valid'),
                                        ], 403);
            }

        }

        $wishlist = UserWishlist::where('type', $type)->where('auction_id', $auc->id)->where('user_id', $user->id)->get();

        if (count($wishlist) > 0) {
            if ($type == UserWishlist::RECALL) {
                return response()->json([
                                            'status'  => '0',
                                            'message' => trans('message.recall_already_added'),
                                        ], 200);
            }
            if ($type == UserWishlist::WISH_LIST) {
                return response()->json([
                                            'status'  => '0',
                                            'message' => trans('message.wishlist_already_added'),
                                        ], 200);
            }
        } else {
            $data['type']       = $type;
            $data['auction_id'] = $auc->id;
            $data['user_id']    = $user->id;
            $response           = UserWishlist::create($data);
            if ($response) {
                if ($type == UserWishlist::RECALL) {
                    Mail::to($user->email)->send(new AuctionRecall());
                    return response()->json([
                                                'status'  => '1',
                                                'message' => trans('message.recall_added_successfully'),
                                            ], 200);
                }
                if ($type == UserWishlist::WISH_LIST) {
                    //  Mail::to($user->email)->send(new AuctionRecall());
                    return response()->json([
                                                'status'  => '1',
                                                'message' => trans('message.wishlist_added_successfully'),
                                            ], 200);
                }

            } else {
                if ($type == UserWishlist::RECALL) {
                    return response()->json([
                                                'status'  => '2',
                                                'message' => trans('message.wishlist_added_issue'),
                                            ], 200);
                }
                if ($type == UserWishlist::WISH_LIST) {
                    return response()->json([
                                                'status'  => '2',
                                                'message' => trans('message.wishlist_added_issue'),
                                            ], 200);
                }
            }
        }


    }


    /**
     * function to bid on auction
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response 0 or 1
     */


    public function addToWishList(Request $request)
    {
        if ($request->has('id')) {
            $auctionId = encrypt_decrypt('decrypt', $request->get('id'));
            $auc       = Auction::find($auctionId);
            if (!$auc) {
                return response()->json([
                                            'isLoggedIn' => '',
                                            'message'    => trans('message.auction_is_not_valid'),
                                        ], 403);
            }
        }
        if ($request->has('user_id')) {
            $user_id = encrypt_decrypt('decrypt', $request->get('user_id'));
            $user    = User::find($user_id);
            if (!$user) {
                return response()->json([
                                            'isLoggedIn' => '',
                                            'message'    => trans('message.user_is_not_valid'),
                                        ], 403);
            }
        }

        if ($request->has('type')) {
            $typeArray[] = UserWishlist::RECALL;
            $typeArray[] = UserWishlist::WISH_LIST;
            $type        = encrypt_decrypt('decrypt', $request->get('type'));
            //echo $type;
            if (!in_array($type, $typeArray)) {
                return response()->json([
                                            'isLoggedIn' => '',
                                            'message'    => trans('message.type_is_not_valid'),
                                        ], 403);
            }

        }

        $wishlist = UserWishlist::where('type', $type)->where('auction_id', $auc->id)->where('user_id', $user->id)->get();

        if (count($wishlist) > 0) {
            UserWishlist::where('type', $type)->where('auction_id', $auc->id)->where('user_id', $user->id)->delete();
            return response()->json([
                                        'status'  => '0',
                                        'message' => trans('message.wishlist_already_added'),
                                    ], 200);
        } else {
            $data['type']       = $type;
            $data['auction_id'] = $auc->id;
            $data['user_id']    = $user->id;
            $response           = UserWishlist::create($data);
            if ($response) {
                if ($type == UserWishlist::RECALL) {
                    Mail::to($user->email)->send(new AuctionRecall());
                    return response()->json([
                                                'status'  => '1',
                                                'message' => trans('message.recall_added_successfully'),
                                            ], 200);
                }
                if ($type == UserWishlist::WISH_LIST) {
                    //  Mail::to($user->email)->send(new AuctionRecall());
                    return response()->json([
                                                'status'  => '1',
                                                'message' => trans('message.wishlist_added_successfully'),
                                            ], 200);
                }

            } else {
                if ($type == UserWishlist::RECALL) {
                    return response()->json([
                                                'status'  => '2',
                                                'message' => trans('message.wishlist_added_issue'),
                                            ], 200);
                }
                if ($type == UserWishlist::WISH_LIST) {
                    return response()->json([
                                                'status'  => '2',
                                                'message' => trans('message.wishlist_added_issue'),
                                            ], 200);
                }
            }
        }


    }


    /**
     * Show the Get Recall List page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAuctionOverview()
    {

        $timezone      = $this->timezone;
        $queueAuctions = Auction::with('product')->where('auction_type', Auction::QUEUE)->where('status', Auction::ACTIVE)->orderBy('priority', 'ASC')->get();
        $liveAuctions  = AuctionQueue::with('product', 'auction', 'bidHistory', 'wishList')->where('status', AuctionQueue::ACTIVE)->where('duration_count', '>', 0)->get();
        return view('user.auctions.auction_overview', compact('liveAuctions', 'timezone', 'queueAuctions'));

    }

    /**
     * Show the Error for wrong token and wrong user id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUserLossAuctionError()
    {


        return view('user.auctions.auction_error');

    }

    /**
     * Show the Error for wrong token and wrong user id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUserLossAuction($token)
    {
        Session::forget('afterplay_route');
        $userId = Auth::id();
        $link   = UserLossAuction::where('link', $token)->where('user_id', $userId)->where('status', UserLossAuction::ACTIVE)->first();

        $data   = ProductAmountCategory::where('minimum', '>=', $link->bid_price)->orWhere('minimum', 0)->get();

        $afterplay_category_id = ProductAfterplayCategory::where('product_id', $link->product_id)->pluck('afterplay_category_id');
        // echo "<pre>";
        // print_r($link->product_id);
        // die();
        $product_id_afterplay  = ProductAfterplayCategory::with('product')->whereIn('afterplay_category_id', $afterplay_category_id)->pluck('product_id');

        //$products = Product::where('status',Product::ACTIVE)->where('id',$link->product_id)->get();
        $products = Product::where('status', Product::ACTIVE)->whereIn('id', $product_id_afterplay)->get();

        //print_r($products);
        $setting = Setting::first();
        // is there is setting record used that else value from constant file is used
        if ($setting) {
            $discount_per_bid_price = $setting->discount_per_bid_price;
        } else {
            $discount_per_bid_price = DISCOUNT_PER_BID_PRICE;
        }
        $discount = (float)$link->bid_count * (float)$discount_per_bid_price;

        return view('user.auctions.products', compact('products', 'discount', 'token'));

    }

    public function getProductDetail($productId)
    {
        $id              = encrypt_decrypt('decrypt', $productId);
        $attribute_value = [];
        $fields          = [];
        $product         = Product::with('category_name', 'product_attributes')->find($id);
        if (count($product->product_attributes)) {
            $attribute_value = json_decode($product->product_attributes[0]->product_attributes, true);
            $attribute_ids   = array_keys($attribute_value);
            $fieldValues     = $attribute_value;
            $fields          = Attribute::whereIn('id', $attribute_ids)->get();

        }
        return view('user.auctions.product_detail', compact('product', 'fields', 'attribute_value'));
    }


//    imp add timeStamp hack


    public function timeStampHack($timerTime)
    {
        //todo: Mathias Hack
        if ($timerTime > 0) {
            $timeStamp = seconds_to_h_m_s($timerTime - 1);
        } else {
            $timeStamp = seconds_to_h_m_s($timerTime);
        }

        return $timeStamp;

    }

}
