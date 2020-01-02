<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Setting;

class SendEmailToAuctionLossers extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    public $startTime;
    public $link;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$link)
    {
        
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        
        return $this->subject(trans('email_template.loss_auction_subject'))
                    ->markdown('emails.send_email_to_auction_looser');
    }
}
