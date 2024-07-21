<?php

namespace Zus1\LaravelAuth\Mail;

use Illuminate\Database\Eloquent\Model;
use Zus1\LaravelAuth\Constant\RouteName;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\URL;

class WelcomeEmail extends Mailable
{
    /**
     * Create a new message instance.
     */
    public function __construct(
        private Model $user,
    ){
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('laravel-auth.email.subject.welcome'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: config('laravel-auth.email.templates.welcome.txt'),
            markdown: config('laravel-auth.email.templates.welcome.markdown'),
            with: [
                'url' => URL::route(RouteName::LOGIN_FORM),
                'name' => sprintf(
                    '%s %s',
                    $this->user->getAttribute('first_name') ?? '',
                    $this->user->getAttribute('last_name') ?? '',
                ),
            ],
        );
    }
}
