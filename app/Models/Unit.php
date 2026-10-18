<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = ['code', 'name', 'dimension', 'decimal_scale', 'is_active'];

    protected function casts(): array
    {
        return ['decimal_scale' => 'integer', 'is_active' => 'boolean'];
    }

    /** @return HasMany<ProductUnit, $this> */
    public function productUnits(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }
}
