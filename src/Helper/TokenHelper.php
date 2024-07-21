<?php

namespace Zus1\LaravelAuth\Helper;

class TokenHelper
{
    private const SYMBOLS = '123456789abcdefgABCDEFG';

    public function getToken(int $length): string
    {
        $token = '';
        for($i = 0; $i < $length; $i++) {
            $token .= self::SYMBOLS[rand(0, strlen(self::SYMBOLS) -1)];
        }

        return $token;
    }

    public function getCode(int $length): int
    {
        $min = (int) ('1'.str_repeat('0', $length -1));
        $max = (int) str_repeat('9', $length);

        return random_int($min, $max);
    }
}
