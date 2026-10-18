<?php

namespace App\Actions\Payables;

use App\Domain\Payables\Enums\PayableStatus;
use App\Models\Payable;
use App\Models\PayablePayment;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\IdempotencyService;
use App\Services\NumberGenerator;
use Illuminate\Validation\ValidationException;

class RecordPayablePayment
{
    public function __construct(private IdempotencyService $idempotency, private NumberGenerator $numbers, private AuditLogger $audit) {}

    /**
     * @param  array{idempotency_key: string, paid_on: string, amount_rupiah: int, method: string, reference?: string|null, notes?: string|null}  $data
     */
    public function handle(User $actor, Payable $payable, array $data): PayablePayment
    {
        if (! $actor->hasPermission('payable_payments.post')) {
            abort(403);
        }
        $result = $this->idempotency->run($actor, 'payable.payment', $data['idempotency_key'], ['payable' => $payable->id, ...$data], PayablePayment::class, fn (int $id): PayablePayment => PayablePayment::query()->findOrFail($id), function () use ($actor, $payable, $data): PayablePayment {
            $locked = Payable::query()->whereKey($payable->id)->lockForUpdate()->firstOrFail();
            $amount = (int) $data['amount_rupiah'];
            if ($amount <= 0 || $amount > $locked->balance_rupiah) {
                throw ValidationException::withMessages(['amount_rupiah' => 'Jumlah pembayaran harus lebih dari nol dan tidak boleh melebihi sisa kewajiban.']);
            }
            $payment = $locked->payments()->create(['number' => $this->numbers->next('BY', now()), 'paid_on' => $data['paid_on'], 'amount_rupiah' => $amount, 'method' => $data['method'], 'reference' => $data['reference'] ?? null, 'notes' => $data['notes'] ?? null, 'status' => 'posted', 'created_by' => $actor->id, 'idempotency_key' => $data['idempotency_key']]);
            $paid = $locked->paid_rupiah + $amount;
            $balance = $locked->principal_rupiah - $paid;
            $status = $balance === 0 ? PayableStatus::Paid : PayableStatus::PartiallyPaid;
            $locked->update(['paid_rupiah' => $paid, 'balance_rupiah' => $balance, 'status' => $status, 'closed_at' => $balance === 0 ? now() : null]);
            $this->audit->record('payable.payment_posted', $actor, $payment, [], ['number' => $payment->number, 'amount_rupiah' => $amount], ['payable' => $locked->number]);

            return $payment;
        });

        return $result;
    }
}
