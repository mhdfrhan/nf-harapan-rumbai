<?php

namespace App\Models;

use App\Domain\Sales\Enums\SaleStatus;
use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $number
 * @property SaleStatus $status
 * @property int $cashier_id
 * @property int $total_rupiah
 * @property Carbon $sold_at
 * @property Collection<int, SaleItem> $items
 * @property SalePayment|null $payment
 */
class Sale extends Model
{
    use HasPublicId;

    protected $fillable = ['number', 'status', 'sold_at', 'cashier_id', 'subtotal_rupiah', 'discount_rupiah', 'total_rupiah', 'notes', 'idempotency_key', 'posted_at', 'voided_at', 'voided_by', 'void_reason'];

    protected function casts(): array
    {
        return ['status' => SaleStatus::class, 'sold_at' => 'datetime', 'subtotal_rupiah' => 'integer', 'discount_rupiah' => 'integer', 'total_rupiah' => 'integer', 'posted_at' => 'datetime', 'voided_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'number';
    }

    /** @return BelongsTo<User, $this> */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /** @return HasMany<SaleItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /** @return HasOne<SalePayment, $this> */
    public function payment(): HasOne
    {
        return $this->hasOne(SalePayment::class);
    }

    /** @return HasOne<SaleVoid, $this> */
    public function voidRecord(): HasOne
    {
        return $this->hasOne(SaleVoid::class);
    }
}
