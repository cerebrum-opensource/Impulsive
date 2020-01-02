<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueryCreated extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $text;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data='',$text,$subject)
    {
       $this->data= $data;
        $this->text = $text;
        $this->subject= $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->subject(trans('email_template.query_subject'))
                    ->markdown('emails.query');
    }
}
