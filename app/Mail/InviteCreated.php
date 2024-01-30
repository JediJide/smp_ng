<?php

namespace App\Mail;

use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class InviteCreated extends Mailable
{
    use Queueable, SerializesModels;

    private Invite $invite;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $invite = $this->invite;

        $url = URL::temporarySignedRoute('auth.registration', now()->addDays(7), [
            'token' => $invite->token,
            'response' => 'yes',
        ]);

        $email_from_address = config('app.mail_from_address');

        return $this->from($email_from_address)
            ->subject('Welcome to CSL Behring Scientific Messaging Platform')
            ->view('emails.invite', compact('invite', 'url'));
    }
}
