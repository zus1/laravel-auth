<?php

namespace Zus1\LaravelAuth\Events;

use Illuminate\Foundation\Events\Dispatchable;

class PasswordResetEvent extends UserEvent
{
    use Dispatchable;
}