<?php

namespace App\Actions\Sales;

use App\Domain\Inventory\Enums\StockMovementType;
use App\Domain\Sales\Enums\SaleStatus;
use App\Domain\Shared\ValueObjects\Quantity;
use App\Models\Sale;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\IdempotencyService;
use App\Services\InventoryLedger;
use Illuminate\Validation\ValidationException;

class VoidSale
{
    public function __construct(private IdempotencyService $idempotency, private InventoryLedger $ledger, private AuditLogger $audit) {}

    public function handle(User $actor, Sale $sale, string $reason, string $key): Sale
    {
        if (! $actor->hasPermission('sales.void')) {
            abort(403);
        }
        $result = $this->idempotency->run($actor, 'sale.void', $key, ['sale' => $sale->id, 'reason' => $reason], Sale::class, fn (int $id): Sale => Sale::query()->findOrFail($id), function () use ($actor, $sale, $reason): Sale {
            $locked = Sale::query()->with(['items.product'])->whereKey($sale->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== SaleStatus::Posted) {
                throw ValidationException::withMessages(['sale' => 'Hanya penjualan yang sudah diposting dapat dibatalkan.']);
            }
            $locked->voidRecord()->create(['voided_by' => $actor->id, 'reason_text' => $reason, 'created_at' => now()]);
            foreach ($locked->items as $item) {
                $this->ledger->post($item->product, StockMovementType::SaleVoid->value, Quantity::from((string) $item->base_quantity), $locked, $actor, 'sale_void', $reason);
            }
            $locked->update(['status' => SaleStatus::Voided, 'voided_at' => now(), 'voided_by' => $actor->id, 'void_reason' => $reason]);
            $this->audit->record('sale.voided', $actor, $locked, ['status' => 'posted'], ['status' => 'voided'], ['reason' => $reason]);

            return $locked;
        });

        return $result;
    }
}
