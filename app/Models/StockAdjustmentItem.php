<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustmentItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['stock_adjustment_id', 'product_id', 'system_quantity', 'physical_quantity', 'quantity_delta', 'notes'];

    protected function casts(): array
    {
        return ['system_quantity' => 'decimal:4', 'physical_quantity' => 'decimal:4', 'quantity_delta' => 'decimal:4'];
    }

    /** @return BelongsTo<StockAdjustment, $this> */
    public function adjustment(): BelongsTo
    {
        return $this->belongsTo(StockAdjustment::class, 'stock_adjustment_id');
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
