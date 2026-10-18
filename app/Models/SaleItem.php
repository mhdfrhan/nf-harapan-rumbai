<?php

namespace App\Models;

use App\Domain\Catalog\Enums\PriceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $base_quantity
 * @property Product $product
 */
class SaleItem extends Model
{
    protected $fillable = ['sale_id', 'product_id', 'product_unit_id', 'product_name', 'sku', 'unit_label', 'conversion_to_base', 'quantity', 'base_quantity', 'price_type', 'unit_price_rupiah', 'discount_rupiah', 'subtotal_rupiah'];

    protected function casts(): array
    {
        return ['conversion_to_base' => 'decimal:4', 'quantity' => 'decimal:4', 'base_quantity' => 'decimal:4', 'price_type' => PriceType::class, 'unit_price_rupiah' => 'integer', 'discount_rupiah' => 'integer', 'subtotal_rupiah' => 'integer'];
    }

    /** @return BelongsTo<Sale, $this> */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** @return BelongsTo<ProductUnit, $this> */
    public function productUnit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class);
    }
}
