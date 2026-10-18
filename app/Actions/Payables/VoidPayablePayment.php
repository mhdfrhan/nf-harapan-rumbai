<?php

namespace App\Actions\Payables;

use App\Domain\Payables\Enums\PayableStatus;
use App\Models\Payable;
use App\Models\PayablePayment;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\IdempotencyService;
use Illuminate\Validation\ValidationException;

class VoidPayablePayment
{
    public function __construct(private IdempotencyService $idempotency, private AuditLogger $audit) {}

    public function handle(User $actor, Payable $payable, PayablePayment $payment, string $reason, string $key): PayablePayment
    {
        if (! $actor->hasPermission('payable_payments.void')) {
            abort(403);
        }
        if ($payment->payable_id !== $payable->id) {
            abort(404);
        }

        return $this->idempotency->run($actor, 'payable.payment_void', $key, ['payable' => $payable->id, 'payment' => $payment->id, 'reason' => $reason], PayablePayment::class, fn (int $id): PayablePayment => PayablePayment::query()->findOrFail($id), function () use ($actor, $payable, $payment, $reason): PayablePayment {
            $lockedPayable = Payable::query()->whereKey($payable->id)->lockForUpdate()->firstOrFail();
            $lockedPayment = PayablePayment::query()->whereKey($payment->id)->lockForUpdate()->firstOrFail();
            if ($lockedPayment->status !== 'posted') {
                throw ValidationException::withMessages(['payment' => 'Hanya pembayaran yang masih diposting dapat dibatalkan.']);
            }
            if ($lockedPayment->amount_rupiah > $lockedPayable->paid_rupiah) {
                throw ValidationException::withMessages(['payment' => 'Saldo kewajiban tidak konsisten. Jalankan rekonsiliasi sebelum membatalkan pembayaran.']);
            }

            $paid = $lockedPayable->paid_rupiah - $lockedPayment->amount_rupiah;
            $balance = $lockedPayable->principal_rupiah - $paid;
            $status = $lockedPayable->due_on?->isPast() ? PayableStatus::Overdue : ($paid > 0 ? PayableStatus::PartiallyPaid : PayableStatus::Unpaid);
            $lockedPayment->update(['status' => 'voided', 'voided_by' => $actor->id, 'voided_at' => now(), 'void_reason' => $reason]);
            $lockedPayable->update(['paid_rupiah' => $paid, 'balance_rupiah' => $balance, 'status' => $status, 'closed_at' => null]);
            $this->audit->record('payable.payment_voided', $actor, $lockedPayment, ['status' => 'posted'], ['status' => 'voided'], ['payable' => $lockedPayable->number, 'reason' => $reason]);

            return $lockedPayment;
        });
    }
}
