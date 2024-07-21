<?php

namespace Zus1\LaravelAuth\Helper;

use Illuminate\Support\Str;

class UserHelper
{
    public function getUserTable(): string
    {
        $userClass = config('laravel-auth.user_class');

        $userClassArr =  explode('\\', $userClass);

        return lcfirst(Str::plural($userClassArr[count($userClassArr)-1]));
    }
}
