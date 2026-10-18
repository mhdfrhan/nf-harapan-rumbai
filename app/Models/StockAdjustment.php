<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockAdjustment extends Model
{
    use HasPublicId;

    protected $fillable = ['number', 'status', 'adjusted_at', 'created_by', 'posted_by', 'reason_code', 'notes', 'idempotency_key'];

    protected function casts(): array
    {
        return ['adjusted_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'number';
    }

    /** @return HasMany<StockAdjustmentItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
