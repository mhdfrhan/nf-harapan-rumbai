<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSequence extends Model
{
    protected $fillable = ['type', 'sequence_date', 'last_value'];

    protected function casts(): array
    {
        return ['last_value' => 'integer'];
    }
}
