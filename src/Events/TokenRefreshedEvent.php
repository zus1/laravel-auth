<?php

namespace Zus1\LaravelAuth\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;

class TokenRefreshedEvent
{
    use Dispatchable;

    public function __construct(
        private Model $user,
        private string $refreshToken,
        private string $newAccessToken
    ){
    }

    public function getUser(): Model
    {
        return $this->user;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getNewAccessToken(): string
    {
        return $this->newAccessToken;
    }
}