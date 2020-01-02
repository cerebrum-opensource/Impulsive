<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\PaymentStatusUpdate::class,
        Commands\PromotionalPackage::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // schedular to update the payment status every minute
        $schedule->command('payment_status:update')
                ->hourly();

        // schedular to update the promo status every minute 
        $schedule->command('live:promotionalpackge')
               ->everyMinute();

        // schedular to update the live auction every minute 
        $schedule->command('live:auctions')
               ->everyFiveMinutes();

        // schedular to Expire the live auction every 5 minute 
        $schedule->command('expire:auctions')
               ->everyFiveMinutes();
            //   ->everyMinute();

        // schedular to move queue to live when count is less than maximun number of allowed auction       
        $schedule->command('live_queue:auctions')
              // ->everyTenMinutes();
               ->everyFiveMinutes();

        $schedule->command('update:bonus_bid_account')
               ->daily();


         // schedular to update the promo status every minute 
        $schedule->command('klarn_payment_status:update')
               ->hourly();

        /*$schedule->command('user_bid_agent:auctions')
               ->cron('* * * * *');*/
       // $schedule->command('live:promotionalpackge')
       //     ->dailyAt('23:55'); 

        // schedular to send email to those user who recall queued auction      
        $schedule->command('recall_auction_user:auctions')
              // ->everyTenMinutes();
               ->everyFiveMinutes();


        // schedular to send email before break time over 
        $schedule->command('breaktime:email')
               ->everyThirtyMinutes();

        // scheduler to check if the CSV file for products is modified or not.       
        $schedule->command('csvfile_update:products')
               ->timezone('Europe/Berlin')
               ->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
