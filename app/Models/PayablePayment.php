<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @property int $id @property string $number @property int $amount_rupiah */
class PayablePayment extends Model
{
    use HasPublicId;

    protected $fillable = ['number', 'payable_id', 'paid_on', 'amount_rupiah', 'method', 'reference', 'notes', 'status', 'created_by', 'voided_by', 'voided_at', 'void_reason', 'idempotency_key'];

    protected function casts(): array
    {
        return ['paid_on' => 'date', 'amount_rupiah' => 'integer', 'voided_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'number';
    }

    /** @return BelongsTo<Payable, $this> */
    public function payable(): BelongsTo
    {
        return $this->belongsTo(Payable::class);
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** @return HasMany<Document, $this> */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'attachable_id')->where('attachable_type', class_basename(self::class));
    }
}
