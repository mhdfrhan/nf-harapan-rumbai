<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $product_id
 * @property string $label
 * @property string $conversion_to_base
 * @property bool $is_active
 * @property Product $product
 * @property Collection<int, ProductPrice> $prices
 */
class ProductUnit extends Model
{
    protected $fillable = ['product_id', 'unit_id', 'label', 'conversion_to_base', 'barcode', 'is_default_sale_unit', 'is_active'];

    protected function casts(): array
    {
        return ['conversion_to_base' => 'decimal:4', 'is_default_sale_unit' => 'boolean', 'is_active' => 'boolean'];
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** @return BelongsTo<Unit, $this> */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /** @return HasMany<ProductPrice, $this> */
    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }
}
