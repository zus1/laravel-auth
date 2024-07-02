<?php

namespace Zus1\LaravelAuth\Controllers;

use Illuminate\Http\JsonResponse;
use Zus1\LaravelAuth\Constant\TokenType;
use Zus1\LaravelAuth\Events\PasswordResetSentEvent;
use Zus1\LaravelAuth\Mail\ResetPasswordEmail;
use Zus1\LaravelAuth\Mail\Send;
use Zus1\LaravelAuth\Repository\UserRepository;
use Zus1\LaravelAuth\Request\ResetPasswordRequest;

class ResetPasswordSend
{
    public function __construct(
        private UserRepository $userRepository,
        private Send $mailer,
    ){
    }

    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $user = $this->userRepository->findByEmailOr404($request->query('email'));

        $this->mailer->send($user, ResetPasswordEmail::class, TokenType::USER_RESET_PASSWORD);

        PasswordResetSentEvent::dispatch($user);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
