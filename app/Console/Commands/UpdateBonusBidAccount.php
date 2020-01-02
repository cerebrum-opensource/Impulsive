<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserBonusBid;
use App\Models\UserBid;
use Log;

class UpdateBonusBidAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:bonus_bid_account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This schedular is used to update the bid account if there is bonus bid.';

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
        $nowData = \Carbon\Carbon::now()->format('Y-m-d');
        $bonusBids = UserBonusBid::where('expire_date','<',$nowData)->where('status',UserBonusBid::ACTIVE)->get();
        foreach ($bonusBids as $bonusBid) {
            UserBid::where('user_id',$bonusBid->user_id)->decrement('bid_count', $bonusBid->bid_count);
            UserBid::where('user_id',$bonusBid->user_id)->decrement('free_bid', $bonusBid->bid_count);

            UserBonusBid::find($bonusBid->id)->update(['status' =>UserBonusBid::EXPIRE]);
        }

        //
        Log::info('Update bonus bid account every night');
    }
}
