<?php

namespace Zus1\LaravelAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $token
 * @property string $created_at
 * @property string $expires_at
 * @property string $type
 * @property bool $active
 */
class Token extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'active' => 'bool',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('laravel-auth.user_class'));
    }
}
