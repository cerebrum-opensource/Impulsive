<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductOrderPlaced extends Mailable
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
       
        return $this->subject(trans('email_template.product_purchase_subject'))
                   // ->view('emails.invite_friend');
                    ->markdown('emails.invoice')
                    ->attachData($this->data, 'rechnung.pdf', [
                        'mime' => 'application/pdf', 
                    ]);

    }
}
