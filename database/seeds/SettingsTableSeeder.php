<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('settings')->insert([
            'free_bid_amount' => 10,
            'invite_bid_amount' => 10,
            'maximum_live_auction' => 6,
            'per_bid_price_raise' => 0.01,
            'per_bid_count_raise' => 1,
            'per_bid_count_descrease' => 1,
            'discount_per_bid_price' => 0.50,
            'bonus_bid_day_count' => 7,
            'instant_purchase_expire_day' => 7,
            'break_start_time' => '18:00:00',
            'break_end_time' => '24:00:00',
        ]);
    }
}
