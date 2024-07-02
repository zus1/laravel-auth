<?php

namespace Zus1\LaravelAuth\Events;

use Illuminate\Foundation\Events\Dispatchable;

class LoggedInEvent extends UserEvent
{
    use Dispatchable;
}