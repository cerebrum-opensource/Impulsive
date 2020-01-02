<?php

namespace App\Listeners;

use App\Mail\ProfileCompleteEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

class SendProfileCompleteEmail
{
    /**
     * Handle the event.
     *
     * @param  PasswordReset  $event
     *
     * @return void
     */
    public function handle(Verified $event)
    {
        $setting = Setting::firstOrFail();
        if(!empty($setting))
        {
            $bid_count = $setting->bonus_bid_day_count;
        }
        else{
            $bid_count = BONUS_BID_DAY_COUNT;
            }
        $user = $event->user;
        Mail::to($user)
            ->send(new ProfileCompleteEmail($user,$bid_count));
    }
}