<?php

namespace Zus1\LaravelAuth\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\LaravelAuth\Events\UserVerifiedEvent;
use Zus1\LaravelAuth\Repository\TokenRepository;

class Verify
{
    public function __construct(
        private TokenRepository $tokenRepository,
    ){
    }

    public function __invoke(Request $request): JsonResponse
    {
        $tokenString = $request->query('token');
        $token = $this->tokenRepository->retrieve($tokenString);

        $user = $token->user()->first();
        $user->setAttribute('active', true);
        $user->save();

        $this->tokenRepository->deactivate($token);

        UserVerifiedEvent::dispatch($user);

        return new JsonResponse(collect($user->toArray())->only('active')->all());
    }

}
