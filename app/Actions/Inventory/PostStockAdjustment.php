<?php

namespace App\Actions\Inventory;

use App\Domain\Inventory\Enums\StockMovementType;
use App\Domain\Shared\ValueObjects\Quantity;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockBalance;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\IdempotencyService;
use App\Services\InventoryLedger;
use App\Services\NumberGenerator;
use Illuminate\Validation\ValidationException;

class PostStockAdjustment
{
    public function __construct(private IdempotencyService $idempotency, private NumberGenerator $numbers, private InventoryLedger $ledger, private AuditLogger $audit) {}

    /** @param array{idempotency_key: string, product_id: int, physical_quantity: int|float|string, reason_code: string, notes: string} $data */
    public function handle(User $actor, array $data): StockAdjustment
    {
        if (! $actor->hasPermission('stock.adjust')) {
            abort(403);
        }
        $result = $this->idempotency->run($actor, 'stock.adjust', $data['idempotency_key'], $data, StockAdjustment::class, fn (int $id): StockAdjustment => StockAdjustment::query()->findOrFail($id), function () use ($actor, $data): StockAdjustment {
            $product = Product::query()->whereKey($data['product_id'])->lockForUpdate()->firstOrFail();
            $balance = StockBalance::query()->firstOrCreate(['product_id' => $product->id], ['quantity_on_hand' => '0.0000']);
            $system = Quantity::from((string) $balance->quantity_on_hand);
            $physical = Quantity::from((string) $data['physical_quantity']);
            if ($physical->scaled < 0) {
                throw ValidationException::withMessages(['physical_quantity' => 'Stok fisik tidak boleh negatif.']);
            }
            $isOpeningBalance = $data['reason_code'] === 'opening_balance';
            if ($isOpeningBalance && StockMovement::query()->where('product_id', $product->id)->exists()) {
                throw ValidationException::withMessages(['reason_code' => 'Stok awal hanya dapat dicatat sebelum produk memiliki riwayat pergerakan. Gunakan perhitungan fisik untuk koreksi berikutnya.']);
            }
            $delta = new Quantity($physical->scaled - $system->scaled);
            if ($delta->scaled === 0) {
                throw ValidationException::withMessages(['physical_quantity' => 'Tidak ada selisih stok untuk diposting.']);
            }
            $adjustment = StockAdjustment::query()->create(['number' => $this->numbers->next('PS', now()), 'adjusted_at' => now(), 'created_by' => $actor->id, 'posted_by' => $actor->id, 'reason_code' => $data['reason_code'], 'notes' => $data['notes'], 'idempotency_key' => $data['idempotency_key']]);
            $adjustment->items()->create(['product_id' => $product->id, 'system_quantity' => $system->decimal(), 'physical_quantity' => $physical->decimal(), 'quantity_delta' => $delta->decimal(), 'notes' => $data['notes']]);
            $type = $isOpeningBalance ? StockMovementType::OpeningBalance->value : ($delta->scaled > 0 ? StockMovementType::AdjustmentIn->value : StockMovementType::AdjustmentOut->value);
            $this->ledger->post($product, $type, $delta, $adjustment, $actor, $data['reason_code'], $data['notes']);
            $this->audit->record('stock.adjusted', $actor, $adjustment, ['quantity' => $system->decimal()], ['quantity' => $physical->decimal()], ['product_id' => $product->id, 'reason' => $data['reason_code']]);

            return $adjustment;
        });

        return $result;
    }
}
