<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Setting;

class FriendInviteAccept extends Mailable
{
    use Queueable, SerializesModels;
   
    public $user;
    public $friend;
    public $invite_bids;
  

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$friend,$invite_bids)
    {
        //
        $this->user = $user;
        $this->friend = $friend;
        $this->invite_bids = $invite_bids;
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
                    ->markdown('emails.invite_friend_accept');
    }
}
