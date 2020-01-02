<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Log;
use App\Models\Auction;
use App\Models\AuctionQueue;
use App\Models\AuctionWinner;
use App\Models\AuctionAutomateAgent;
use App\Models\UserBid;
use App\Models\Setting;
use App\Models\UserLossAuction;
use App\Mail\BidWon;
use App\User;
use Mail; 

use Illuminate\Support\Facades\DB;

class ExpireAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire the Auction that have passed end date/time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
       Log::info('Auction is Expired from cron');

    //    die();
        $setting = Setting::first();
        
        $expireAuctions = AuctionQueue::with('bidHistory')->where('status',AuctionQueue::ACTIVE)->get();

        foreach ($expireAuctions as $expireAuction) {
            Log::info($expireAuction->duration_count);

            if($expireAuction->duration_count > 0)
            {   
              //  Log::info('Auction is running');
            }   
            else{
                if(count($expireAuction->bidHistory)){
                    $user_id = $expireAuction->bidHistory[0]->user->id;
                }
                else {
                    $user_id = 0; 
                }
                $auctionWinner['auction_queue_id'] = $expireAuction->id;
                $auctionWinner['user_id'] = $user_id;
                $auctionWinner['auction_id'] = $expireAuction->auction_id;
                $auctionWinner['product_id'] = $expireAuction->product_id;
                $auctionWinner['bid_count'] = $expireAuction->bid_count;
                $auctionWinner['bid_price'] = $expireAuction->bid_price;
                $winner = AuctionWinner::where('auction_queue_id',$expireAuction->id)->count();
                if($winner < 1){
                    AuctionWinner::create($auctionWinner);
                    Log::info("winner found");
                    $userData = User::find($user_id);
                    if($userData){
                        Mail::to($userData->email)->send(new BidWon($userData));
                    }
                    

                    $expireAuction->auctionLoseUser($user_id);
                }

                $agentSetups = AuctionAutomateAgent::where('auction_queue_id',$expireAuction->id)->where('status',AuctionAutomateAgent::ACTIVE)->where('remaining_bid','>',0)->get();
                if(count($agentSetups) > 0){
                    foreach ($agentSetups as $key => $agentSetup) {
                        UserBid::where('user_id',$user_id)->increment('bid_count', $agentSetup->remaining_bid);
                        AuctionAutomateAgent::find($agentSetup->id)->update([
                                    'remaining_bid' => 0,
                                    'total_bids' => 0,
                                    'status' => AuctionAutomateAgent::NOT_ACTIVE,
                                ]);
                    }
                }
                AuctionQueue::where('id',$expireAuction->id)->update(['status'=>AuctionQueue::NOT_ACTIVE]);
                Auction::where('id',$expireAuction->auction_id)->update(['status'=>Auction::ENDED]);
            }
            
        }

    }
}
