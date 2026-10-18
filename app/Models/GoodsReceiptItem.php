<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsReceiptItem extends Model
{
    protected $fillable = ['goods_receipt_id', 'product_id', 'product_unit_id', 'product_name', 'sku', 'unit_label', 'conversion_to_base', 'quantity', 'base_quantity', 'unit_cost_rupiah', 'subtotal_rupiah'];

    protected function casts(): array
    {
        return ['conversion_to_base' => 'decimal:4', 'quantity' => 'decimal:4', 'base_quantity' => 'decimal:4', 'unit_cost_rupiah' => 'integer', 'subtotal_rupiah' => 'integer'];
    }

    /** @return BelongsTo<GoodsReceipt, $this> */
    public function receipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class, 'goods_receipt_id');
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
