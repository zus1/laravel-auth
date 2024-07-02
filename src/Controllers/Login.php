<?php

namespace Zus1\LaravelAuth\Controllers;

use Illuminate\Database\Eloquent\Model;
use Zus1\LaravelAuth\Constant\TokenType;
use Zus1\LaravelAuth\Dto\AuthenticationResponseDto;
use Zus1\LaravelAuth\Events\LoggedInEvent;
use Zus1\LaravelAuth\Repository\TokenRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelAuth\Request\LoginRequest;

class Login
{
    public function __construct(
        private TokenRepository $repository,
    ) {
    }

    public function __invoke(LoginRequest $request): JsonResponse
    {
        if(Auth::attempt([...$request->input(), 'active' => true]) === true) {
            /** @var Model $user */
            $user = Auth::user();

            $accessToken = $this->repository->create($user, TokenType::ACCESS)->token;
            $refreshToken = $this->repository->create($user, TokenType::REFRESH)->token;

            LoggedInEvent::dispatch($user);

            return new JsonResponse(AuthenticationResponseDto::create($user, $accessToken, $refreshToken));
        }

        throw new AuthenticationException('Could not login user, please check your credentials');
    }
}
