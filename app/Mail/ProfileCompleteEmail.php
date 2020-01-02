<?php


namespace App\Mail;

use Illuminate\Mail\Mailable;

class ProfileCompleteEmail extends Mailable
{
    /** @var $user */
    public $user;
    public $bid_count;

    /**
     * Create a new message instance.
     *
     * @param $user
     */
    public function __construct($user,$bid_count)
    {
        $this->user = $user;
        $this->bid_count = $bid_count;
        //$this->text = 'Data';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this
            ->subject(trans('email_template.registration_successful_subject'))
            ->text('emails.auth.complete_profile_plain')
            ->markdown('emails.auth.complete_profile');
    }
}
