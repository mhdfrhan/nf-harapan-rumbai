<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @property string $quantity_on_hand @property int $version */
class StockBalance extends Model
{
    protected $primaryKey = 'product_id';

    public $incrementing = false;

    protected $fillable = ['product_id', 'quantity_on_hand', 'version', 'last_movement_id'];

    protected function casts(): array
    {
        return ['quantity_on_hand' => 'decimal:4', 'version' => 'integer'];
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
