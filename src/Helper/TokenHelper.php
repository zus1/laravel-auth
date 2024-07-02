<?php

namespace Zus1\LaravelAuth\Helper;

use Zus1\LaravelAuth\Constant\TokenType;

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
}
