<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Log;
use App\Models\Auction;
use App\Models\AuctionQueue;
use Illuminate\Support\Facades\DB;

class LiveAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'live:auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the Auction Status Every Minute';

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

        DB::enableQueryLog();
        $todayDate =  \Carbon\Carbon::now()->format('Y-m-d H:i').':00';
        $todayDateFuture =  \Carbon\Carbon::now()->addMinutes(5)->format('Y-m-d H:i').':00';
       // $data = Auction::whereBetween('start_time', array($todayDate, $todayDateFuture))->where('status',Auction::ACTIVE)->where('auction_type',Auction::PLANNED)->get();

        $plannedAuctions = Auction::whereBetween('start_time', array($todayDate, $todayDateFuture))->where('status',Auction::ACTIVE)->where('auction_type',Auction::PLANNED)->get();
        $data = [];

        foreach ($plannedAuctions as $plannedAuction) {
            $auctionQueueData['auction_id'] = $plannedAuction->id;
            $auctionQueueData['product_id'] = $plannedAuction->product_id;
            $auctionQueueData['bid_count'] = 0;
            $auctionQueueData['bid_price'] = 0;
            $auctionQueueData['status'] = AuctionQueue::ACTIVE;
            $auctionQueueData['duration'] = $plannedAuction->duration;
            $auctionQueueData['auction_type'] = $plannedAuction->auction_type;
            $auctionQueueData['final_countdown'] = $plannedAuction->final_countdown;
            $auctionQueueData['duration_count'] = h_m_s_to_seconds($plannedAuction->duration);
            $durationTime = explode(':',$plannedAuction->duration);
            $str = '';
            $str.='+'.$durationTime[0].' hour';
            $str.='+'.$durationTime[1].' minutes';
            $str.='+'.$durationTime[2].' seconds';
            $auctionQueueData['end_time'] = date('Y-m-d H:i:s',strtotime($str,strtotime($plannedAuction->start_date)));

            $final_countdownTime = strtotime($auctionQueueData['end_time']);
            $time = $final_countdownTime - ($plannedAuction->final_countdown);
            $date = date("Y-m-d H:i:s", $time);
            $auctionQueueData['final_countdown_start_time'] = $date;

            $data[]= $auctionQueueData;

        }
        AuctionQueue::insert($data);
        $t = Auction::whereBetween('start_time', array($todayDate, $todayDateFuture))->where('status',Auction::ACTIVE)->where('auction_type',Auction::PLANNED)->update(['status'=>Auction::CURRENTLY_RUNNING]);

        
         Log::info('Update the Auction');
         Log::info($t);
         Log::info($todayDateFuture);
        // Log::info($todayDatePast);
    }
}
