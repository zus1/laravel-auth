<?php

namespace Zus1\LaravelAuth\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\LaravelAuth\Constant\Token\TokenType;
use Zus1\LaravelAuth\Dto\RefreshTokenResponseDto;
use Zus1\LaravelAuth\Events\TokenRefreshedEvent;
use Zus1\LaravelAuth\Repository\TokenRepository;

class RefreshToken
{
    public function __construct(
        private TokenRepository $tokenRepository,
    ){
    }

    public function __invoke(Request $request): JsonResponse
    {
        $refreshTokenString = $request->query('refresh_token');

        if($this->tokenRepository->isExpired($refreshTokenString)) {
            $oldRefreshToken = $this->tokenRepository->retrieve($refreshTokenString, expired: true);
            $user = $oldRefreshToken->user()->first();
            $newRefreshToken = $this->tokenRepository->create($user, TokenType::REFRESH)->token;
        }

        $refreshToken = $newRefreshToken ?? $this->tokenRepository->retrieve($refreshTokenString);
        $user = $user ?? $refreshToken->user()->first();
        $newAccessToken = $this->tokenRepository->create($user, TokenType::ACCESS)->token;

        TokenRefreshedEvent::dispatch($user, $refreshToken, $newAccessToken);

        return new JsonResponse(
            RefreshTokenResponseDto::create($newRefreshToken ?? $refreshTokenString, $newAccessToken)
        );
    }
}
