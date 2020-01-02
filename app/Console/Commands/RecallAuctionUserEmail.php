<?php

namespace App\Console\Commands;
use App\Models\Auction;
use App\Models\AuctionQueue;
use App\Models\Setting;
use App\Models\UserWishlist;
use Log;
use Illuminate\Console\Command;
use Mail;
use App\Mail\SendRecallUserEmail;
use Illuminate\Http\Request;
use DB;
use Config;
use Carbon\Carbon;

class RecallAuctionUserEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recall_auction_user:auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It is used to send the email to those users who recall some auction';

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
        Log::info('send email to recal user');
        $allLiveAuctions = AuctionQueue::where('status',1);
        
        $setting = Setting::first();
        // is there is setting record used that else value from constant file is used
        if($setting){
            $maximum_live_auction = $setting->maximum_live_auction;
        }
        else {
            $maximum_live_auction = MAXIMUM_LIVE_AUCTION;
        }
        // $checkTimeOfAuction = $allLiveAuctions->where('duration_count','<=',3600)->get();
        // dd($checkTimeOfAuction);
        $count = $allLiveAuctions->count();
        Log::info('send email to recal user count '. $count);
        if($count <= (int)$maximum_live_auction){
                $checkTimeOfAuction = $allLiveAuctions->where('duration_count','<=', RECALL_AUCTION_TIME)->get();
                if($checkTimeOfAuction->count() > 0){
                    $data = Auction::with('wishListUser')->where('auction_type',1)->where('status',1)->orderBy('priority','desc')->limit($checkTimeOfAuction->count())->get();
                    if(!empty($data)){
                        foreach ($data as $value) {
                            foreach ($value->wishListUser as $val) {
                               Mail::to($val->user['email'])->send(new SendRecallUserEmail($val->user['username']));
                               $update=  UserWishlist::where('id', $val['id'])->update(['send_email' => 1]);
                            }
                            
                        }
                    }
                }
        }

        //Code to send email before 1 hour from auction start time who recall this auction
        $auctionData = Auction::with('wishListUser')->where('auction_type',2)->where('status',1)->get();
        foreach ($auctionData as $value) {
            $nowTime = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $checkTime = strtotime($value['start_date']) - strtotime($nowTime);
            if($checkTime <= RECALL_AUCTION_TIME){
                foreach ($value->wishListUser as $val) {
                   Mail::to($val->user['email'])->send(new SendRecallUserEmail($val->user['username']));
                   $update=  UserWishlist::where('id', $val['id'])->update(['send_email' => 1]);
                }
            }
       }
       
    }
}
