<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Setting;

class BreakTime extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    public $startTime;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$startTime)
    {
        
        $this->user = $user;
        $this->startTime = $startTime;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        
        return $this->subject(trans('email_template.break_time_subject'))
                   // ->view('emails.invite_friend');
                    ->markdown('emails.break_time');
    }
}
