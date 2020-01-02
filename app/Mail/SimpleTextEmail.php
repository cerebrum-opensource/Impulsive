<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SimpleTextEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $text,$sender_mail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sender_mail,$text,$subject)
    {
        $this->sender_mail = $sender_mail;
        $this->text = $text;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        
        return $this->from($this->sender_mail)
                    ->subject($this->subject)
                    ->markdown('emails.invite_friend_sender');
    }
}
