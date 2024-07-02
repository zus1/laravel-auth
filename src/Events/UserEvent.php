<?php

namespace Zus1\LaravelAuth\Events;

use Illuminate\Database\Eloquent\Model;

abstract class UserEvent
{
    public function __construct(
        private Model $user,
    ){
    }

    public function getUser(): Model
    {
        return $this->user;
    }
}