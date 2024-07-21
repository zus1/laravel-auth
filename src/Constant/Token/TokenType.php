<?php


namespace Zus1\LaravelAuth\Constant\Token;

use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;
use function Zus1\LaravelAuth\Constant\config;

class TokenType
{
    public const USER_VERIFICATION = 'user_verification';
    public const USER_RESET_PASSWORD = 'user_reset_password';
    public const ACCESS = 'access';
    public const REFRESH = 'refresh';

    public static function expiresAt(string $type, Carbon $createAt): string
    {
        $expiresAt = match ($type) {
            self::USER_VERIFICATION => $createAt->addMinutes(config('laravel-auth.token.expires_in.user_verification_token')),
            self::USER_RESET_PASSWORD => $createAt->addMinutes(config('laravel-auth.token.expires_in.reset_password_token')),
            self::ACCESS => $createAt->addMinutes(config('laravel-auth.token.expires_in.access_token')),
            self::REFRESH => $createAt->addMinutes(config('laravel-auth.token.expires_in.refresh_token')),
            default => static::customExpiresAt($createAt),
        };

        return $expiresAt->format('Y-m-d H:i:s');
    }

    public static function length(string $type): int
    {
        return match ($type) {
            self::USER_VERIFICATION => config('laravel-auth.token.length.user_verification_token'),
            self::USER_RESET_PASSWORD => config('laravel-auth.token.length.reset_password_token'),
            self::ACCESS => config('laravel-auth.token.length.access_token'),
            self::REFRESH => config('laravel-auth.token.length.refresh_token'),
            default => static::customLength(),
        };
    }

    public static function action(string $type): string
    {
        return match ($type) {
            self::USER_VERIFICATION,
            self::USER_RESET_PASSWORD,
            self::ACCESS,
            self::REFRESH => TokenAction::TOKEN,
            default => self::customAction(),
        };
    }

    protected static function customExpiresAt(Carbon $createdAt): Carbon
    {
        throw new HttpException(500, 'Unknown token type');
    }

    protected static function customLength(): int
    {
        throw new HttpException(500, 'Unknown token length');
    }

    protected static function customAction(): string
    {
        return 'getToken';
    }
}
