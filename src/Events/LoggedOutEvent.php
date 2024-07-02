<?php

namespace Zus1\LaravelAuth\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Auth;

class LoggedOutEvent
{
    use Dispatchable;

    public function __construct(
        private ?Model $user = null
    ){
        /** @var Model $user */
        $user = Auth::user();

        $this->user = $user;
    }

    public function gerUser(): Model
    {
        return $this->user;
    }
}