<?php

namespace Zus1\LaravelAuth\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;

class EmailResentEvent
{
    use Dispatchable;

    public function __construct(
        private Model $user,
        private string $mailable
    ){
    }

    public function getUser(): Model
    {
        return $this->user;
    }

    public function getMailable(): string
    {
        return $this->mailable;
    }
}
