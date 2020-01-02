<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\BreakTime;
use App\Models\Setting;
use App\Models\AuctionQueue;
use App\Models\UserBidHistory;
use App\User;
use Mail;
use Log;



class BreakTimeEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'breaktime:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        Log::info('send email to every user for breaktime start');

        $nowTime = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d H:i:s');

       // $breaktime = \Carbon\Carbon::now()->subDays(1)->timezone('Europe/Berlin')->format('Y-m-d');
        $breaktime = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d');

        Log::info('Now Time'.$nowTime);
        $setting = Setting::first();
        // is there is setting record used that else value from constant file is used
        if($setting){
            $break_start_time = $setting->break_start_time;
        }
        else {
            $break_start_time = AUCTION_START_TIME;
        }
        $breaktime = $breaktime.' '.$break_start_time;
        Log::info('Break Time'.$breaktime);

        $auction_ids = AuctionQueue::where('status',AuctionQueue::ACTIVE)->where('duration_count','>','0')->pluck('id');
        $user_in_bid_history = UserBidHistory::whereIn('auction_id',$auction_ids)->distinct()->pluck('user_id');

        $checkTime = strtotime($breaktime) - strtotime($nowTime);


        if($checkTime <= 3600 && $checkTime > 1800){

           $userData = User::whereIn('id',$user_in_bid_history)->where('status','=',User::ACTIVE)
                   ->get()
                   ->toArray();

                $startTime = $setting->break_time_label;
                $subject =  $startTime;
                $search = ':00';
                $replace = ' Uhr';
                $time = strrev(implode(strrev($replace), explode(strrev($search), strrev($subject), 2)));
                foreach($userData as $user)
                 {
                     Mail::to($user['email'])->send(new BreakTime($user['username'],$time));
                 } 

        }

        Log::info('Time Pending'.$checkTime);
    }
}
