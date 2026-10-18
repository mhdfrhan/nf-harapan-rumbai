<?php

namespace App\Services;

use App\Domain\Catalog\Enums\PriceType;
use App\Domain\Shared\ValueObjects\Quantity;
use App\Models\ProductPrice;
use App\Models\ProductUnit;

class ProductPriceManager
{
    public function set(ProductUnit $unit, PriceType $type, ?int $amountRupiah, string $minimumQuantity = '1.0000'): ?ProductPrice
    {
        $activePrices = $unit->prices()
            ->where('price_type', $type)
            ->where('is_active', true)
            ->lockForUpdate()
            ->orderByDesc('id')
            ->get();

        $current = $activePrices->first();
        $minimum = Quantity::from($minimumQuantity);
        $unchanged = $amountRupiah !== null
            && $activePrices->count() === 1
            && $current instanceof ProductPrice
            && $current->amount_rupiah === $amountRupiah
            && Quantity::from($current->minimum_quantity)->scaled === $minimum->scaled;

        if ($unchanged) {
            return $current;
        }

        $changedAt = now();
        $unit->prices()
            ->where('price_type', $type)
            ->where('is_active', true)
            ->update(['is_active' => false, 'effective_until' => $changedAt]);

        if ($amountRupiah === null) {
            return null;
        }

        return $unit->prices()->create([
            'price_type' => $type,
            'amount_rupiah' => $amountRupiah,
            'minimum_quantity' => $minimum->decimal(),
            'effective_from' => $changedAt,
            'effective_until' => null,
            'is_active' => true,
        ]);
    }
}
