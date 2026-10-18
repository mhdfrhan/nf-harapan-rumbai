<?php

namespace App\Models;

use App\Domain\Inventory\Enums\StockMovementType;
use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasPublicId;

    protected $fillable = ['product_id', 'movement_type', 'quantity_delta', 'balance_after', 'reference_type', 'reference_id', 'occurred_at', 'actor_id', 'reason_code', 'notes'];

    protected function casts(): array
    {
        return ['movement_type' => StockMovementType::class, 'quantity_delta' => 'decimal:4', 'balance_after' => 'decimal:4', 'occurred_at' => 'datetime'];
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** @return BelongsTo<User, $this> */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
