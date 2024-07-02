<?php

namespace Zus1\LaravelAuth\Dto;

use Illuminate\Database\Eloquent\Model;

class AuthenticationResponseDto implements \JsonSerializable
{
    private string $accessToken;
    private string $refreshToken;
    private string $firstName;
    private string $lastName;
    private string $email;

    public static function create(Model $user, string $accessToken, string $refreshToken): self
    {
        $instance = new self();
        $instance->accessToken = $accessToken;
        $instance->refreshToken = $refreshToken;
        $instance->firstName = $user->getAttribute('first_name') ?? '';
        $instance->lastName = $user->getAttribute('last_name') ?? '';
        $instance->email = $user->getAttribute('email');

        return $instance;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
