<?php

namespace App\Http\Controllers;

use App\Domain\Catalog\Enums\PriceType;
use App\Domain\Shared\ValueObjects\Quantity;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Services\AuditLogger;
use App\Services\ProductPriceManager;
use App\Support\RupiahInput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductUnitController extends Controller
{
    public function store(Request $request, Product $product, AuditLogger $audit, ProductPriceManager $priceManager): RedirectResponse
    {
        return $this->persist($request, $product, null, $audit, $priceManager);
    }

    public function update(Request $request, Product $product, ProductUnit $productUnit, AuditLogger $audit, ProductPriceManager $priceManager): RedirectResponse
    {
        abort_unless($productUnit->product_id === $product->id, 404);

        return $this->persist($request, $product, $productUnit, $audit, $priceManager);
    }

    private function persist(Request $request, Product $product, ?ProductUnit $productUnit, AuditLogger $audit, ProductPriceManager $priceManager): RedirectResponse
    {
        $data = $request->validate([
            'unit_id' => ['required', 'exists:units,id'],
            'label' => ['required', 'string', 'max:80', Rule::unique('product_units')->where('product_id', $product->id)->ignore($productUnit)],
            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('product_units')->ignore($productUnit)],
            'conversion_to_base' => ['required', 'decimal:0,4', 'gt:0'],
            'retail_price' => ['required'],
            'wholesale_price' => ['nullable'],
            'wholesale_minimum' => ['nullable', 'decimal:0,4', 'gt:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $before = $productUnit?->only(['unit_id', 'label', 'barcode', 'conversion_to_base', 'is_active']) ?? [];
        if ($productUnit) {
            $before['active_prices'] = $this->activePriceSnapshot($productUnit->loadMissing('prices'));
        }
        $saved = DB::transaction(function () use ($data, $product, $productUnit, $priceManager): ProductUnit {
            if ($productUnit) {
                $productUnit = ProductUnit::query()->whereKey($productUnit->id)->lockForUpdate()->firstOrFail();
                $used = DB::table('sale_items')->where('product_unit_id', $productUnit->id)->exists()
                    || DB::table('goods_receipt_items')->where('product_unit_id', $productUnit->id)->exists();
                $conversionChanged = (int) $data['unit_id'] !== $productUnit->unit_id
                    || Quantity::from((string) $data['conversion_to_base'])->scaled !== Quantity::from((string) $productUnit->conversion_to_base)->scaled;
                if ($used && $conversionChanged) {
                    throw ValidationException::withMessages(['conversion_to_base' => 'Satuan yang sudah dipakai transaksi tidak dapat diubah konversinya. Nonaktifkan lalu buat satuan jual baru.']);
                }
            } else {
                $productUnit = new ProductUnit(['product_id' => $product->id, 'is_default_sale_unit' => false]);
            }

            if (! $data['is_active'] && $productUnit->is_default_sale_unit) {
                throw ValidationException::withMessages(['is_active' => 'Satuan jual utama tidak dapat dinonaktifkan.']);
            }
            $productUnit->fill(['unit_id' => $data['unit_id'], 'label' => $data['label'], 'barcode' => $data['barcode'] ?: null, 'conversion_to_base' => $data['conversion_to_base'], 'is_active' => (bool) $data['is_active']])->save();
            $priceManager->set($productUnit, PriceType::Retail, RupiahInput::parse($data['retail_price'], 'retail_price'));
            if (! empty($data['wholesale_price'])) {
                $priceManager->set($productUnit, PriceType::Wholesale, RupiahInput::parse($data['wholesale_price'], 'wholesale_price'), (string) ($data['wholesale_minimum'] ?? 1));
            } else {
                $priceManager->set($productUnit, PriceType::Wholesale, null);
            }

            return $productUnit;
        });

        $after = $saved->only(['unit_id', 'label', 'barcode', 'conversion_to_base', 'is_active']);
        $after['active_prices'] = $this->activePriceSnapshot($saved->load('prices'));
        $audit->record($productUnit ? 'catalog.product_unit_updated' : 'catalog.product_unit_created', $request->user(), $saved, $before, $after);

        return back()->with('status', 'Satuan jual berhasil disimpan.');
    }

    /** @return list<array{type: string, amount_rupiah: int, minimum_quantity: string}> */
    private function activePriceSnapshot(ProductUnit $productUnit): array
    {
        $snapshot = [];
        foreach ($productUnit->prices as $price) {
            if ($price->is_active) {
                $snapshot[] = ['type' => $price->price_type->value, 'amount_rupiah' => $price->amount_rupiah, 'minimum_quantity' => $price->minimum_quantity];
            }
        }
        usort($snapshot, fn (array $left, array $right): int => $left['type'] <=> $right['type']);

        return $snapshot;
    }
}
