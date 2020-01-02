<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PackageOrderPlaced extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $text;
    public $package_detail;
    public $bid_day_count;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$text,$user,$package_detail,$bid_day_count)
    {
        $this->data= $data;
        $this->text = $text;
        $this->user= $user;
        $this->package_detail= $package_detail;
        $this->bid_day_count = $bid_day_count;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        return $this->subject(trans('email_template.package_purchase_subject'))
                   // ->view('emails.invite_friend');
                    ->markdown('emails.package_invoice')
                    ->attachData($this->data, 'rechnung.pdf', [
                        'mime' => 'application/pdf',
                    ]);

    }
}
