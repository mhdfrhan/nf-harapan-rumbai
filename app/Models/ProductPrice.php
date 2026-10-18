<?php

namespace App\Models;

use App\Domain\Catalog\Enums\PriceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property PriceType $price_type
 * @property int $amount_rupiah
 * @property string $minimum_quantity
 * @property Carbon|null $effective_from
 * @property Carbon|null $effective_until
 * @property bool $is_active
 */
class ProductPrice extends Model
{
    protected $fillable = ['product_unit_id', 'price_type', 'amount_rupiah', 'minimum_quantity', 'effective_from', 'effective_until', 'is_active'];

    protected function casts(): array
    {
        return ['price_type' => PriceType::class, 'amount_rupiah' => 'integer', 'minimum_quantity' => 'decimal:4', 'effective_from' => 'datetime', 'effective_until' => 'datetime', 'is_active' => 'boolean'];
    }

    /** @return BelongsTo<ProductUnit, $this> */
    public function productUnit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class);
    }
}
