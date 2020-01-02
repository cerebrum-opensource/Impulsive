<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\User;
use App\Models\Auction;
use App\Models\AuctionQueue;
use App\Models\Setting;
use Carbon\Carbon;
use Config;
use Auth;
//use App\Services\{Slider as SliderService};

class HomepageController extends BaseController
{
    /**
     * Initilize the controler and set the timezone using functional dependency injection
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->timezone = user_time_zone();
        ///$this->slider = new SliderService();
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\View
    */
    public function view(Request $request){
        
        $liveAuctionIds = AuctionQueue::pluck('id')->toArray();
       // $sliders = $this->slider->getSliderList();

        // encrypted ids to send in ajax from frontend
        $auctionsIds = [];
        foreach ($liveAuctionIds as $key => $liveAuctionId) {
            $auctionsId['id'] = encrypt_decrypt('encrypt', $liveAuctionId);
            $auctionsIds[] = $auctionsId;
        }

        $timezone = $this->timezone;
        $liveAuctions = AuctionQueue::with('product','auction','bidHistory')->where('status',AuctionQueue::ACTIVE)->get();
        $queueAuctions = Auction::with('product')->where('auction_type',Auction::QUEUE)->where('status',Auction::ACTIVE)->orderBy('priority', 'ASC')->get();
        $plannedAuctions = Auction::with('product')->where('auction_type',Auction::PLANNED)->where('status',Auction::ACTIVE)->get();
        $expiredAuctions = AuctionQueue::with('product','auction','auctionWinner')->where('status',AuctionQueue::NOT_ACTIVE)->orderBy('id', 'desc')->take(3)->get();

        $user = [];
        $isLoggedIn = false;
        if(!Auth::guest()){
            $isLoggedIn = true;
            $user = Auth::user();
        }
        

        return view('homepage', compact('liveAuctions','queueAuctions','plannedAuctions','expiredAuctions','auctionsIds','timezone','user','isLoggedIn'));
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\View
    */
    /*
    public function dashboard(Request $request){
        $timezone = $this->timezone;
        $liveAuctions = AuctionQueue::with('product','auction','bidHistory')->where('status',AuctionQueue::ACTIVE)->get();
        return view('dashboard', compact('liveAuctions','timezone'));
    }*/

    /**
     * Function to check user is login and have bid count before bidding
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response : Json
    */

    public function getCheckLogin(Request $request){

        $setting = Setting::first();
        $nowDateGermany = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d H:i:s'); 

        $auctionStartTime = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d').' '.$setting->break_start_time; 
        $auctionEndTime   = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d').' '.$setting->break_end_time;

        $break = auction_time_helper($auctionStartTime,$auctionEndTime);




        $isLoggedIn = false;
        if(!Auth::guest()){
            $isLoggedIn = true;
            $user = User::with('userBid')->find(Auth::id());
            $count = 0;

            if(!$user->is_complete){
                $message = trans('message.not_complete_profile').'<a href='.route("user_profile").'> Complete profile. </a>';
            }
            else if(count($user->userBid) && $user->userBid[0]->bid_count > 0){
                $count = $user->userBid[0]->bid_count;
                $message = '';
            }
            else {
                 $message = trans('message.not_enough_bid');
            }
            $auctionData = AuctionQueue::find(encrypt_decrypt('decrypt', $request->get('id')));

            if($request->has('bidCount') && $request->get('bidCount') && $message ==''){
                $remaningBid = $count-$request->get('bidCount');
                $startType[] = IMMEDIATLY;
                $startType[] = FOUR_HOURS;
                $startType[] = FIVE_MINUTES;
                $startType[] = FINAL_COUNTDOWN;
                $type = $request->get('start_type');
                if($remaningBid < 0){
                     $count = 0; 
                     $message = trans('message.bid_number_greater_than_bid_had');
                }

                if(!in_array($type,$startType)){
                     $count = 0; 
                     $message = trans('message.start_time_not_valid');
                }
                else {
                    

                    if($auctionData){
                        $nowDate = \Carbon\Carbon::now()->timezone($this->timezone)->format('Y-m-d H:i:s'); 
                       // $diff = strtotime($auctionData->end_time) - strtotime($nowDate);
                        $diff = $auctionData->duration_count;
                        if($diff < 14400 && $type == FOUR_HOURS){
                            $count = 0; 
                            $message = trans('message.time_is_not_valid');
                        }
                        if($auctionData->duration_count <= 0){
                            $count = 0; 
                            $message = trans('message.auction_expired');
                        }

                    }
                    else {
                        $count = 0; 
                        $message = trans('message.session_token_expire');
                    }

                }
                
            }

            if($auctionData){
                if($auctionData->duration_count <= 0){
                    $count = 0; 
                    $message = trans('message.auction_expired');
                }
            }

            if($break){
                $message = trans('message.not_allowed_bid');
                $isLoggedIn = false;
            }

            return response()->json(['count'=>$count,'user_id'=>encrypt_decrypt('encrypt', Auth::id()),'userid'=>Auth::id(),'isLoggedIn' =>$isLoggedIn,'id'=>$request->get('id'),'message'=>$message],200);
        }
        $message = trans('message.not_login_user_message');
        if($break){
                $message = trans('message.not_allowed_bid');
                $isLoggedIn = false;
            }
      /*  if($diffGermany < 0 && $diffGermanyEnd > 0){
                $message = trans('message.not_allowed_bid');
        }*/
        return response()->json(['isLoggedIn' =>$isLoggedIn,'message'=>$message,'id'=>$request->get('id')],200);

    }


     public function getCheckLoginOverview(Request $request){

        
        $isLoggedIn = false;
        if(!Auth::guest()){
            $isLoggedIn = true;
            $user = User::with('userBid')->find(Auth::id());
            $count = 0;

            if(!$user->is_complete){
                $message = trans('message.not_complete_profile').'<a href='.route("user_profile").'> Complete profile. </a>';
            }
            else if(count($user->userBid)){
                $count = $user->userBid[0]->bid_count;
                $message = '';
            }
            else {
                 $message = trans('message.not_enough_bid');
            }

            if($request->has('bidCount') && $request->get('bidCount') && $message ==''){
                $remaningBid = $count-$request->get('bidCount');
                $startType[] = IMMEDIATLY;
                $startType[] = FOUR_HOURS;
                $startType[] = FIVE_MINUTES;
                $startType[] = FINAL_COUNTDOWN;
                $type = $request->get('start_type');
                if($remaningBid < 0){
                     $count = 0; 
                     $message = trans('message.bid_number_greater_than_bid_had');
                }

                if(!in_array($type,$startType)){
                     $count = 0; 
                     $message = trans('message.start_time_not_valid');
                }
                else {
                    $auctionData = AuctionQueue::find(encrypt_decrypt('decrypt', $request->get('id')));


                   // die();
                    if($auctionData){
                        $nowDate = \Carbon\Carbon::now()->timezone($this->timezone)->format('Y-m-d H:i:s'); 
                        $diff = strtotime($auctionData->end_time) - strtotime($nowDate);
                        if($diff < 14400 && $type == FOUR_HOURS){
                            $count = 0; 
                            $message = trans('message.time_is_not_valid');
                        }

                    }
                    else {
                        $count = 0; 
                        $message = trans('message.session_token_expire');
                    }

                }
                
            }

            return response()->json(['count'=>$count,'user_id'=>encrypt_decrypt('encrypt', Auth::id()),'userid'=>Auth::id(),'isLoggedIn' =>$isLoggedIn,'id'=>$request->get('id'),'message'=>$message],200);
        }
        return response()->json(['isLoggedIn' =>$isLoggedIn,'message'=>trans('message.not_login_user_message'),'id'=>$request->get('id')],200);

    }



}
