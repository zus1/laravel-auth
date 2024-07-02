<?php

namespace Zus1\LaravelAuth\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Zus1\LaravelAuth\Events\PasswordResetEvent;
use Zus1\LaravelAuth\Repository\TokenRepository;
use Zus1\LaravelAuth\Request\ResetPasswordRequest;

class ResetPassword
{
    public function __construct(
        private TokenRepository $tokenRepository
    ) {
    }

    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $token = $this->tokenRepository->retrieve($request->query('token'));

        $user = $token->user()->first();
        $user->setAttribute('password', Hash::make($request->input('password')));
        $user->save();

        $this->tokenRepository->deactivate($token);

        PasswordResetEvent::dispatch($user);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
