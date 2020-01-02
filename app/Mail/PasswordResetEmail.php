<?php


namespace App\Mail;

use Illuminate\Mail\Mailable;

class PasswordResetEmail extends Mailable
{
    /** @var $user */
    private $user;

    /**
     * Create a new message instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
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
            ->subject(trans('label.pssword_changed'))
            ->text('emails.auth.password_changed_plain')
            ->markdown('emails.auth.password_changed');
    }
}
