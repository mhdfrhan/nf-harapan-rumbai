<?php

namespace App\Http\Controllers;

use App\Domain\Catalog\Enums\PriceType;
use App\Domain\Shared\ValueObjects\Quantity;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockBalance;
use App\Models\Unit;
use App\Services\AuditLogger;
use App\Services\ProductPriceManager;
use App\Support\RupiahInput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()->with(['category', 'baseUnit', 'stockBalance', 'saleUnits.prices'])->search($request->string('q')->toString())->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->input('status') === 'active'))->orderBy('name')->paginate(20)->withQueryString();

        return view('pages.catalog.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('pages.catalog.products.form', ['product' => null, 'categories' => Category::where('is_active', true)->orderBy('name')->get(), 'units' => Unit::where('is_active', true)->orderBy('name')->get()]);
    }

    public function edit(Product $product): View
    {
        $product->load('saleUnits.prices');

        return view('pages.catalog.products.form', ['product' => $product, 'categories' => Category::where('is_active', true)->orderBy('name')->get(), 'units' => Unit::where('is_active', true)->orderBy('name')->get()]);
    }

    public function store(Request $request, AuditLogger $audit, ProductPriceManager $priceManager): RedirectResponse
    {
        return $this->persist($request, null, $audit, $priceManager);
    }

    public function update(Request $request, Product $product, AuditLogger $audit, ProductPriceManager $priceManager): RedirectResponse
    {
        return $this->persist($request, $product, $audit, $priceManager);
    }

    private function persist(Request $request, ?Product $product, AuditLogger $audit, ProductPriceManager $priceManager): RedirectResponse
    {
        $request->user()->hasPermission($product ? 'products.update' : 'products.create') || abort(403);
        $data = $request->validate(['name' => ['required', 'string', 'max:180'], 'sku' => ['required', 'string', 'max:64', Rule::unique('products')->ignore($product)], 'barcode' => ['nullable', 'string', 'max:100', Rule::unique('products')->ignore($product)], 'category_id' => ['required', 'exists:categories,id'], 'base_unit_id' => ['required', 'exists:units,id'], 'description' => ['nullable', 'string', 'max:1000'], 'low_stock_threshold' => ['required', 'decimal:0,4', 'min:0'], 'conversion_to_base' => ['required', 'decimal:0,4', 'gt:0'], 'unit_label' => ['required', 'string', 'max:80'], 'retail_price' => ['required'], 'wholesale_price' => ['nullable'], 'wholesale_minimum' => ['nullable', 'decimal:0,4', 'gt:0'], 'is_active' => ['nullable', 'boolean'], 'lock_version' => [$product ? 'required' : 'nullable', 'integer', 'min:1']]);
        $before = $product?->only(['name', 'sku', 'category_id', 'base_unit_id', 'is_active']) ?? [];
        if ($product) {
            $before['active_prices'] = $this->activePriceSnapshot($product->loadMissing('saleUnits.prices'));
        }
        $saved = DB::transaction(function () use ($data, $product, $priceManager): Product {
            $isEditing = $product !== null;
            if ($product) {
                $product = Product::query()->whereKey($product->id)->lockForUpdate()->firstOrFail();
                if ($product->lock_version !== (int) $data['lock_version']) {
                    throw ValidationException::withMessages(['name' => 'Data produk telah diubah pengguna lain. Muat ulang halaman sebelum menyimpan kembali.']);
                }
                if ((int) $data['base_unit_id'] !== $product->base_unit_id && DB::table('stock_movements')->where('product_id', $product->id)->exists()) {
                    throw ValidationException::withMessages(['base_unit_id' => 'Satuan dasar tidak dapat diubah setelah produk memiliki pergerakan stok.']);
                }
            } else {
                $product = new Product;
            }
            $product->fill(['name' => $data['name'], 'sku' => Str::upper($data['sku']), 'barcode' => $data['barcode'] ?: null, 'category_id' => $data['category_id'], 'base_unit_id' => $data['base_unit_id'], 'description' => $data['description'] ?? null, 'low_stock_threshold' => $data['low_stock_threshold'], 'allows_negative_stock' => false, 'is_active' => (bool) ($data['is_active'] ?? true), 'lock_version' => $isEditing ? $product->lock_version + 1 : 1]);
            $product->save();
            $unit = $product->saleUnits()->where('is_default_sale_unit', true)->first() ?? $product->saleUnits()->make();
            if ($unit->exists) {
                $used = DB::table('sale_items')->where('product_unit_id', $unit->id)->exists() || DB::table('goods_receipt_items')->where('product_unit_id', $unit->id)->exists();
                $conversionChanged = (int) $data['base_unit_id'] !== $unit->unit_id || Quantity::from((string) $data['conversion_to_base'])->scaled !== Quantity::from((string) $unit->conversion_to_base)->scaled;
                if ($used && $conversionChanged) {
                    throw ValidationException::withMessages(['conversion_to_base' => 'Konversi satuan yang sudah dipakai transaksi tidak dapat diubah.']);
                }
            }
            $unit->fill(['unit_id' => $data['base_unit_id'], 'label' => $data['unit_label'], 'conversion_to_base' => $data['conversion_to_base'], 'is_default_sale_unit' => true, 'is_active' => true])->save();
            $priceManager->set($unit, PriceType::Retail, RupiahInput::parse($data['retail_price'], 'retail_price'));
            if (! empty($data['wholesale_price'])) {
                $priceManager->set($unit, PriceType::Wholesale, RupiahInput::parse($data['wholesale_price'], 'wholesale_price'), (string) ($data['wholesale_minimum'] ?? 1));
            } else {
                $priceManager->set($unit, PriceType::Wholesale, null);
            }
            StockBalance::query()->firstOrCreate(['product_id' => $product->id], ['quantity_on_hand' => '0.0000']);

            return $product;
        });
        $after = $saved->only(['name', 'sku', 'category_id', 'base_unit_id', 'is_active']);
        $after['active_prices'] = $this->activePriceSnapshot($saved->load('saleUnits.prices'));
        $audit->record($product ? 'catalog.product_updated' : 'catalog.product_created', $request->user(), $saved, $before, $after);

        return redirect()->route('catalog.products.index')->with('status', 'Produk berhasil disimpan.');
    }

    /** @return list<array{unit: string, type: string, amount_rupiah: int, minimum_quantity: string}> */
    private function activePriceSnapshot(Product $product): array
    {
        $snapshot = [];
        foreach ($product->saleUnits as $unit) {
            foreach ($unit->prices as $price) {
                if ($price->is_active) {
                    $snapshot[] = ['unit' => $unit->label, 'type' => $price->price_type->value, 'amount_rupiah' => $price->amount_rupiah, 'minimum_quantity' => $price->minimum_quantity];
                }
            }
        }
        usort($snapshot, fn (array $left, array $right): int => [$left['unit'], $left['type']] <=> [$right['unit'], $right['type']]);

        return $snapshot;
    }
}
