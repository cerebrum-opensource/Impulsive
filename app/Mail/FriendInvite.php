<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Setting;

class FriendInvite extends Mailable
{
    use Queueable, SerializesModels;
    public $token;
    public $user;
    public $friendInviteData;
    public $invite_bids;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token,$user,$friendInviteData,$invite_bids)
    {
        //
        $this->token = $token;
        $this->user = $user;
        $this->friendInviteData = $friendInviteData;
        if(!empty($invite_bids)){
            $this->invite_bids = $invite_bids;
        }
        else{
            $this->invite_bids = INVITE_BID_AMOUNT;
        }
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        
        return $this->subject(trans('email_template.invite_send_subject'))
                   // ->view('emails.invite_friend');
                    ->markdown('emails.invite_friend');
    }
}
