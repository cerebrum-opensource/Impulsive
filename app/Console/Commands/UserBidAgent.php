<?php

namespace App\Console\Commands;
use App\Models\Auction;
use App\Models\AuctionQueue;
use App\Models\AuctionAutomateAgent;
use Log;

use Illuminate\Console\Command;

class UserBidAgent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user_bid_agent:auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It is used to check if any Bid agent is setup for any auction and than bid every second';

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

/* 
        $count = 0;
        while ($count < 59) {
            $startTime =  \Carbon\Carbon::now();

            $nowTime = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
           Log::info('Bid Agent Start');
            Log::info($nowTime);
            Log::info('END');*/
           /* $endTime = \Carbon\Carbon::now();
            $totalDuration = $endTime->diffInSeconds($startTime);
            if($totalDuration > 0) {
                $count +=  $totalDuration;
            }
            else {
                $count++;
            }
            sleep(1);
        }

        */
        //
    }
}
