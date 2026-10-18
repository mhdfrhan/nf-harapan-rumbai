<?php

namespace App\Models;

use App\Domain\Payables\Enums\PayableStatus;
use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $number
 * @property PayableStatus $status
 * @property int $principal_rupiah
 * @property int $paid_rupiah
 * @property int $balance_rupiah
 * @property Carbon|null $due_on
 * @property Collection<int, PayablePayment> $payments
 */
class Payable extends Model
{
    use HasPublicId;

    protected $fillable = ['number', 'supplier_id', 'goods_receipt_id', 'status', 'principal_rupiah', 'paid_rupiah', 'balance_rupiah', 'due_on', 'closed_at'];

    protected function casts(): array
    {
        return ['status' => PayableStatus::class, 'principal_rupiah' => 'integer', 'paid_rupiah' => 'integer', 'balance_rupiah' => 'integer', 'due_on' => 'date', 'closed_at' => 'datetime'];
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

    /** @return BelongsTo<GoodsReceipt, $this> */
    public function receipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class, 'goods_receipt_id');
    }

    /** @return HasMany<PayablePayment, $this> */
    public function payments(): HasMany
    {
        return $this->hasMany(PayablePayment::class);
    }

    public function derivedStatus(): PayableStatus
    {
        if ($this->status === PayableStatus::Voided) {
            return PayableStatus::Voided;
        }
        if ($this->balance_rupiah === 0) {
            return PayableStatus::Paid;
        }
        if ($this->due_on?->isPast()) {
            return PayableStatus::Overdue;
        }

        return $this->paid_rupiah > 0 ? PayableStatus::PartiallyPaid : PayableStatus::Unpaid;
    }
}
