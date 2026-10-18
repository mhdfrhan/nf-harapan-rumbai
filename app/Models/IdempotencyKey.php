<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class IdempotencyKey extends Model
{
    use Prunable;

    protected $fillable = ['key', 'user_id', 'action', 'request_hash', 'result_type', 'result_id', 'status', 'expires_at'];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime'];
    }

    /** @return Builder<static> */
    public function prunable(): Builder
    {
        return static::query()->where('status', 'completed')->where('expires_at', '<', now());
    }
}
