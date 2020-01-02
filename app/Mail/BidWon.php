<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Setting;

class BidWon extends Mailable
{
    use Queueable, SerializesModels;
    public $userData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userData)
    {
        //
        $this->userData = $userData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        
        return $this->subject(trans('email_template.bid_won_subject'))
                   // ->view('emails.invite_friend');
                    ->markdown('emails.bid_won');
    }
}
