<?php

namespace App\Services;

use App\Domain\Shared\ValueObjects\Quantity;
use App\Models\Product;
use App\Models\StockBalance;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class InventoryLedger
{
    public function post(Product $product, string $movementType, Quantity $delta, Model $reference, User $actor, ?string $reason = null, ?string $notes = null): StockMovement
    {
        StockBalance::query()->firstOrCreate(['product_id' => $product->id], ['quantity_on_hand' => '0.0000', 'version' => 0]);
        $balance = StockBalance::query()->whereKey($product->id)->lockForUpdate()->firstOrFail();
        $current = Quantity::from((string) $balance->quantity_on_hand);
        $next = new Quantity($current->scaled + $delta->scaled);

        if ($next->scaled < 0 && ! $product->allows_negative_stock) {
            throw ValidationException::withMessages(['items' => "Stok {$product->name} tidak mencukupi."]);
        }

        $movement = StockMovement::query()->create([
            'product_id' => $product->id,
            'movement_type' => $movementType,
            'quantity_delta' => $delta->decimal(),
            'balance_after' => $next->decimal(),
            'reference_type' => class_basename($reference),
            'reference_id' => $reference->getKey(),
            'occurred_at' => now(),
            'actor_id' => $actor->id,
            'reason_code' => $reason,
            'notes' => $notes,
        ]);

        $balance->update(['quantity_on_hand' => $next->decimal(), 'version' => $balance->version + 1, 'last_movement_id' => $movement->id]);

        return $movement;
    }
}
