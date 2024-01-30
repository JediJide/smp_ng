<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Welcome extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $user = $this->user;
        $email_from_address = config('app.mail_from_address');
        $ui_url = config('app.app_ui_url'); //env("APP_UI_URL");

        return $this->from($email_from_address)
            ->view('emails.welcome', compact('user', 'ui_url'));
    }
}
