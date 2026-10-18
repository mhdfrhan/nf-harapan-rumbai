<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** @property array<string, mixed>|null $value_json */
class Setting extends Model
{
    protected $fillable = ['key', 'value_json', 'type', 'is_sensitive'];

    protected function casts(): array
    {
        return ['value_json' => 'array', 'is_sensitive' => 'boolean'];
    }
}
