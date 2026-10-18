<?php

namespace App\Models;

use App\Domain\Procurement\Enums\GoodsReceiptStatus;
use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $number
 * @property GoodsReceiptStatus $status
 * @property int $total_rupiah
 * @property Payable|null $payable
 */
class GoodsReceipt extends Model
{
    use HasPublicId;

    protected $fillable = ['number', 'supplier_id', 'supplier_document_number', 'status', 'received_on', 'subtotal_rupiah', 'total_rupiah', 'payment_state', 'initial_payment_rupiah', 'due_on', 'notes', 'created_by', 'posted_by', 'posted_at', 'idempotency_key'];

    protected function casts(): array
    {
        return ['status' => GoodsReceiptStatus::class, 'received_on' => 'date', 'subtotal_rupiah' => 'integer', 'total_rupiah' => 'integer', 'initial_payment_rupiah' => 'integer', 'due_on' => 'date', 'posted_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'number';
    }

    /** @return BelongsTo<Supplier, $this> */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /** @return HasMany<GoodsReceiptItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    /** @return HasOne<Payable, $this> */
    public function payable(): HasOne
    {
        return $this->hasOne(Payable::class);
    }

    /** @return HasMany<Document, $this> */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'attachable_id')->where('attachable_type', class_basename(self::class));
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
