<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Models\Package;
use Illuminate\Support\Facades\DB;

class PromotionalPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'live:promotionalpackge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the Promotional Package Status Every Minute';

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
        $todayDate =  \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $todayDateFuture =  \Carbon\Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');
        $todayDatePast =  \Carbon\Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s');
        Package::whereBetween('start_date', array($todayDate, $todayDateFuture))->where('status',Package::DRAFT)->update(['status'=>Package::ACTIVE]);

        Package::whereBetween('end_date', array($todayDatePast, $todayDate))->where('status',Package::ACTIVE)->update(['status'=>Package::DRAFT]);
      //  Package::where(DB::raw('DATE(end_date)'),'<=', DB::raw('curdate()'))->where('status',Package::ACTIVE)->update(['status'=>Package::DRAFT]);


       //  Package::where(DB::raw('DATE(start_date)'), DB::raw('curdate()'))->where('status',Package::DRAFT)->update(['status'=>Package::ACTIVE]);
        // Package::where(DB::raw('DATE(end_date)'),'<=', DB::raw('curdate()'))->where('status',Package::ACTIVE)->update(['status'=>Package::DRAFT]);
         Log::info('Update the Package');
         Log::info($todayDate);
         Log::info($todayDateFuture);
         Log::info($todayDatePast);
       //  Log::info(dd(DB::getQueryLog()));
    }
}
