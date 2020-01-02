<?php

namespace App\Console\Commands;
use App\Models\Auction;
use App\Models\AuctionQueue;
use App\Models\Setting;
use Log;
use Illuminate\Console\Command;

class LiveQueueAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'live_queue:auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It is used to check if the live auction is less than six then it live the queue auction by priority';

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
        $currentlyRunningAuctions = AuctionQueue::where('status',AuctionQueue::ACTIVE)->count();
        $nowTime = \Carbon\Carbon::now()->format('Y-m-d H:i:s');

        $setting = Setting::first();
        // is there is setting record used that else value from constant file is used
        if($setting){
            $maximum_live_auction = $setting->maximum_live_auction;
        }
        else {
            $maximum_live_auction = MAXIMUM_LIVE_AUCTION;
        }

        if($currentlyRunningAuctions < $maximum_live_auction){

                $pending = $maximum_live_auction-$currentlyRunningAuctions;

                $queuesData = []; 
                $queuesid = []; 
                $queues = Auction::where('status',Auction::ACTIVE)->where('auction_type',Auction::QUEUE)->orderBy('priority', 'asc')->take($pending)->get();

                foreach ($queues as $key => $auction) {
                    $auctionQueueData['auction_id'] = $auction->id;
                    $auctionQueueData['product_id'] = $auction->product_id;
                    $auctionQueueData['bid_count'] = 0;
                    $auctionQueueData['bid_price'] = 0;
                    $auctionQueueData['status'] = AuctionQueue::ACTIVE;
                    $auctionQueueData['auction_type'] = $auction->auction_type;
                    $auctionQueueData['duration'] = $auction->duration;
                    $auctionQueueData['duration_count'] = h_m_s_to_seconds($auction->duration);
                    $auctionQueueData['final_countdown'] = $auction->final_countdown;
                   /* $durationTime = explode(':',$auction->duration);
                    $str = '';
                    $str.='+'.$durationTime[0].' hour';
                    $str.='+'.$durationTime[1].' minutes';
                    $str.='+'.$durationTime[2].' seconds';
                    $auctionQueueData['end_time'] = date('Y-m-d H:i:s',strtotime($str,strtotime($nowTime)));

                    $final_countdownTime = strtotime($auctionQueueData['end_time']);
                    $time = $final_countdownTime - ($auction->final_countdown);
                    $date = date("Y-m-d H:i:s", $time);
                    $auctionQueueData['final_countdown_start_time'] = $date;*/
                    $queuesData[] = $auctionQueueData;
                    $queuesid[] = $auction->id; 
                }

                if(!empty($queuesData)){
                    AuctionQueue::insert($queuesData); 
                    Auction::whereIn('id',$queuesid)->update(['status'=>Auction::CURRENTLY_RUNNING]);
                }
                
              //  Log::info('pending');
              //  Log::info($queuesid);
                //Log::info($pending);
               // Log::info($queues);

              }
      //  Log::info($currentlyRunningAuctions);
        Log::info('Change the queue to live');
    }
}
