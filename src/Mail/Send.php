<?php

namespace Zus1\LaravelAuth\Mail;

use Illuminate\Database\Eloquent\Model;
use Zus1\LaravelAuth\Constant\TokenType;
use Illuminate\Support\Facades\Mail;
use Zus1\LaravelAuth\Repository\TokenRepository;
use Zus1\LaravelAuth\Repository\UserRepository;

class Send
{
    public function __construct(
        private TokenRepository $tokenRepository,
        private UserRepository $userRepository
    ){
    }

    public function resend(string $email, string $type): void
    {
        $user = $this->userRepository->findByEmailOr404($email);
        $mailable = '';

        if($type === TokenType::USER_VERIFICATION) {
            $this->send($user, $mailable = VerificationEmail::class,TokenType::USER_VERIFICATION);
        }
        if($type === TokenType::USER_RESET_PASSWORD) {
            $this->send($user, $mailable = ResetPasswordEmail::class, TokenType::USER_RESET_PASSWORD);
        }

        EmailResentEvent::dispatch($user, $mailable);
    }

    public function send(Model $user, string $mailable, ?string $tokenType = ''): void
    {
        if($tokenType !== '') {
            $token = $this->tokenRepository->create($user, $tokenType);
        }
        Mail::to($user->getAttribute('email'))->send(new $mailable($user, $token ?? null));
    }
}
