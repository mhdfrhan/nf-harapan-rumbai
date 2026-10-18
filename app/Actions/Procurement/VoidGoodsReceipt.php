<?php

namespace App\Actions\Procurement;

use App\Domain\Inventory\Enums\StockMovementType;
use App\Domain\Payables\Enums\PayableStatus;
use App\Domain\Procurement\Enums\GoodsReceiptStatus;
use App\Domain\Shared\ValueObjects\Quantity;
use App\Models\GoodsReceipt;
use App\Models\Payable;
use App\Models\PayablePayment;
use App\Models\Product;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\IdempotencyService;
use App\Services\InventoryLedger;
use Illuminate\Validation\ValidationException;

class VoidGoodsReceipt
{
    public function __construct(private IdempotencyService $idempotency, private InventoryLedger $ledger, private AuditLogger $audit) {}

    public function handle(User $actor, GoodsReceipt $receipt, string $reason, string $idempotencyKey): GoodsReceipt
    {
        if (! $actor->hasPermission('purchases.void')) {
            abort(403);
        }

        return $this->idempotency->run(
            $actor,
            'receipt.void',
            $idempotencyKey,
            ['receipt_id' => $receipt->id, 'reason' => $reason],
            GoodsReceipt::class,
            fn (int $id): GoodsReceipt => GoodsReceipt::query()->findOrFail($id),
            function () use ($actor, $receipt, $reason): GoodsReceipt {
                $locked = GoodsReceipt::query()->whereKey($receipt->id)->lockForUpdate()->firstOrFail();
                if ($locked->status !== GoodsReceiptStatus::Posted) {
                    throw ValidationException::withMessages(['reason' => 'Hanya penerimaan berstatus posted yang dapat dibatalkan.']);
                }

                $locked->load(['items.product', 'payable.payments']);
                if ($locked->payable) {
                    $this->voidPayableWhenSafe($actor, $locked->payable, $locked->initial_payment_rupiah, $reason);
                }

                foreach ($locked->items as $item) {
                    /** @var Product $product */
                    $product = $item->product;
                    $baseQuantity = Quantity::from((string) $item->base_quantity);
                    $this->ledger->post($product, StockMovementType::PurchaseVoid->value, new Quantity(-$baseQuantity->scaled), $locked, $actor, 'receipt_void', $reason);
                }

                $locked->update(['status' => GoodsReceiptStatus::Voided, 'payment_state' => 'voided']);
                $this->audit->record('receipt.voided', $actor, $locked, ['status' => GoodsReceiptStatus::Posted->value], ['status' => GoodsReceiptStatus::Voided->value], ['reason' => $reason]);

                return $locked;
            },
        );
    }

    private function voidPayableWhenSafe(User $actor, Payable $payable, int $initialPayment, string $reason): void
    {
        $locked = Payable::query()->whereKey($payable->id)->lockForUpdate()->firstOrFail();
        $postedPayments = $locked->payments()->where('status', 'posted')->lockForUpdate()->orderBy('id')->get();

        $hasOnlyExpectedInitialPayment = $initialPayment === 0
            ? $postedPayments->isEmpty()
            : $postedPayments->count() === 1 && $postedPayments->first()?->amount_rupiah === $initialPayment;
        if (! $hasOnlyExpectedInitialPayment) {
            throw ValidationException::withMessages(['reason' => 'Penerimaan tidak dapat dibatalkan karena kewajibannya sudah memiliki pembayaran lanjutan. Void pembayaran tersebut terlebih dahulu.']);
        }

        /** @var PayablePayment|null $initial */
        $initial = $postedPayments->first();
        $initial?->update(['status' => 'voided', 'voided_by' => $actor->id, 'voided_at' => now(), 'void_reason' => "Pembalik void penerimaan: {$reason}"]);
        $locked->update(['status' => PayableStatus::Voided, 'paid_rupiah' => 0, 'balance_rupiah' => 0, 'closed_at' => now()]);
    }
}
