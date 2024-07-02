<?php

namespace Zus1\LaravelAuth\Dto;

class RefreshTokenResponseDto implements \JsonSerializable
{
    private string $accessToken;
    private string $refreshToken;

    public static function create(string $refreshToken, string $accessToken): self
    {
        $instance = new self();
        $instance->accessToken = $accessToken;
        $instance->refreshToken = $refreshToken;

        return $instance;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
