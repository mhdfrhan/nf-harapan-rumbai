<?php

namespace App\Actions\Sales;

use App\Domain\Catalog\Enums\PriceType;
use App\Domain\Inventory\Enums\StockMovementType;
use App\Domain\Sales\Enums\SaleStatus;
use App\Domain\Shared\ValueObjects\Quantity;
use App\Models\ProductPrice;
use App\Models\ProductUnit;
use App\Models\Sale;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\IdempotencyService;
use App\Services\InventoryLedger;
use App\Services\NumberGenerator;
use Illuminate\Validation\ValidationException;

class PostSale
{
    public function __construct(private IdempotencyService $idempotency, private NumberGenerator $numbers, private InventoryLedger $ledger, private AuditLogger $audit) {}

    /**
     * @param array{
     *   idempotency_key: string,
     *   items: non-empty-list<array{product_unit_id: int, quantity: int|float|string}>,
     *   received_rupiah: int,
     *   payment_method?: string,
     *   notes?: string|null
     * } $data
     */
    public function handle(User $actor, array $data): Sale
    {
        if (! $actor->hasPermission('sales.create')) {
            abort(403);
        }

        $sale = $this->idempotency->run($actor, 'sale.post', $data['idempotency_key'], $data, Sale::class, fn (int $id): Sale => Sale::query()->findOrFail($id), function () use ($actor, $data): Sale {
            $resolved = [];
            $subtotal = 0;
            foreach ($data['items'] as $index => $item) {
                $unit = ProductUnit::query()->with(['product', 'prices'])->whereKey($item['product_unit_id'])->where('is_active', true)->first();
                if (! $unit || ! $unit->product->is_active) {
                    throw ValidationException::withMessages(["items.{$index}" => 'Produk atau satuan tidak tersedia.']);
                }
                $quantity = Quantity::from((string) $item['quantity']);
                if ($quantity->scaled <= 0) {
                    throw ValidationException::withMessages(["items.{$index}.quantity" => 'Jumlah harus lebih dari nol.']);
                }
                $price = $this->resolvePrice($unit, $quantity);
                $lineSubtotal = $quantity->rupiahSubtotal($price->amount_rupiah);
                $subtotal += $lineSubtotal;
                $resolved[] = compact('unit', 'quantity', 'price', 'lineSubtotal');
            }
            if ($subtotal <= 0) {
                throw ValidationException::withMessages(['items' => 'Keranjang penjualan belum berisi item yang valid.']);
            }
            $received = (int) $data['received_rupiah'];
            if ($received < $subtotal) {
                throw ValidationException::withMessages(['received_rupiah' => 'Uang diterima kurang dari total penjualan.']);
            }

            $sale = Sale::query()->create(['number' => $this->numbers->next('PJ', now()), 'status' => SaleStatus::Posted, 'sold_at' => now(), 'cashier_id' => $actor->id, 'subtotal_rupiah' => $subtotal, 'discount_rupiah' => 0, 'total_rupiah' => $subtotal, 'notes' => $data['notes'] ?? null, 'idempotency_key' => $data['idempotency_key'], 'posted_at' => now()]);
            foreach ($resolved as $line) {
                $unit = $line['unit'];
                $quantity = $line['quantity'];
                $price = $line['price'];
                $base = $quantity->multiply(Quantity::from((string) $unit->conversion_to_base));
                $sale->items()->create(['product_id' => $unit->product_id, 'product_unit_id' => $unit->id, 'product_name' => $unit->product->name, 'sku' => $unit->product->sku, 'unit_label' => $unit->label, 'conversion_to_base' => $unit->conversion_to_base, 'quantity' => $quantity->decimal(), 'base_quantity' => $base->decimal(), 'price_type' => $price->price_type, 'unit_price_rupiah' => $price->amount_rupiah, 'discount_rupiah' => 0, 'subtotal_rupiah' => $line['lineSubtotal']]);
                $this->ledger->post($unit->product, StockMovementType::Sale->value, new Quantity(-$base->scaled), $sale, $actor);
            }
            $sale->payment()->create(['method' => $data['payment_method'] ?? 'cash', 'amount_rupiah' => $subtotal, 'received_rupiah' => $received, 'change_rupiah' => $received - $subtotal, 'status' => 'posted']);
            $this->audit->record('sale.posted', $actor, $sale, [], ['number' => $sale->number, 'total_rupiah' => $subtotal]);

            return $sale;
        });

        return $sale;
    }

    private function resolvePrice(ProductUnit $unit, Quantity $quantity): ProductPrice
    {
        $now = now();
        $active = $unit->prices->filter(fn (ProductPrice $price) => $price->is_active && (! $price->effective_from || $price->effective_from <= $now) && (! $price->effective_until || $price->effective_until >= $now));
        $wholesale = $active->where('price_type', PriceType::Wholesale)->filter(fn (ProductPrice $price) => Quantity::from((string) $price->minimum_quantity)->scaled <= $quantity->scaled)->sortByDesc('minimum_quantity')->first();
        $price = $wholesale ?: $active->where('price_type', PriceType::Retail)->sortByDesc('effective_from')->first();
        if (! $price || $price->amount_rupiah <= 0) {
            throw ValidationException::withMessages(['items' => "Harga aktif untuk {$unit->product->name} belum tersedia."]);
        }

        return $price;
    }
}
