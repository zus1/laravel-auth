<?php

namespace Zus1\LaravelAuth\Trait;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait Token
{
    public function tokens(): ?HasMany
    {
        return method_exists($this, 'hasMany') ?  $this->hasMany(\Zus1\LaravelAuth\Models\Token::class) : null;
    }
}