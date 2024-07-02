<?php

namespace Zus1\LaravelAuth\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UserVerifiedEvent extends UserEvent
{
    use Dispatchable;
}