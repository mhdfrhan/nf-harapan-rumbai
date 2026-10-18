<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @property int $change_rupiah */
class SalePayment extends Model
{
    protected $fillable = ['sale_id', 'method', 'amount_rupiah', 'received_rupiah', 'change_rupiah', 'reference', 'status'];

    protected function casts(): array
    {
        return ['amount_rupiah' => 'integer', 'received_rupiah' => 'integer', 'change_rupiah' => 'integer'];
    }

    /** @return BelongsTo<Sale, $this> */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
