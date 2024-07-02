<?php

namespace Zus1\LaravelAuth\Events;

use Illuminate\Foundation\Events\Dispatchable;

class PasswordResetSentEvent extends UserEvent
{
    use Dispatchable;
}