<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LossOrderPlaced extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $text;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$text,$user)
    {
        $this->data= $data;
        $this->text = $text;
        $this->user= $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        return $this->subject(trans('email_template.loss_auction_invoice'))
                   // ->view('emails.invite_friend');
                    ->markdown('emails.loss_auction_invoice')
                    ->attachData($this->data, 'rechnung.pdf', [
                        'mime' => 'application/pdf', 
                    ]);

    }
}
