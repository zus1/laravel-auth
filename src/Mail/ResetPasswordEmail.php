<?php

namespace Zus1\LaravelAuth\Mail;

use Illuminate\Database\Eloquent\Model;
use Zus1\LaravelAuth\Constant\RouteName;
use Zus1\LaravelAuth\Models\Token;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\URL;

class ResetPasswordEmail extends Mailable
{
    /**
     * Create a new message instance.
     */
    public function __construct(
        private Model $user,
        private Token $token,
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('laravel-auth.email.subject.reset_password'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: 'mail.authentication.reset-password-txt',
            markdown: 'mail.authentication.reset-password',
            with: [
                'url' => URL::route(RouteName::RESET_PASSWORD, ['token' => $this->token->token]),
                'first_name' => $this->user->getAttribute('first_name') ?? '',
                'last_name' => $this->user->getAttribute('last_name') ?? '',
            ],
        );
    }
}
