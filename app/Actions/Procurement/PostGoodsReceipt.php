<?php

namespace App\Actions\Procurement;

use App\Domain\Inventory\Enums\StockMovementType;
use App\Domain\Payables\Enums\PayableStatus;
use App\Domain\Procurement\Enums\GoodsReceiptStatus;
use App\Domain\Shared\ValueObjects\Quantity;
use App\Models\GoodsReceipt;
use App\Models\ProductUnit;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\IdempotencyService;
use App\Services\InventoryLedger;
use App\Services\NumberGenerator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PostGoodsReceipt
{
    public function __construct(private IdempotencyService $idempotency, private NumberGenerator $numbers, private InventoryLedger $ledger, private AuditLogger $audit) {}

    /**
     * @param array{
     *   idempotency_key: string,
     *   supplier_id: int,
     *   received_on: string,
     *   items: non-empty-list<array{product_unit_id: int, quantity: int|float|string, unit_cost_rupiah: int}>,
     *   supplier_document_number?: string|null,
     *   initial_payment_rupiah?: int,
     *   payment_method?: string,
     *   due_on?: string|null,
     *   notes?: string|null
     * } $data
     */
    public function handle(User $actor, array $data): GoodsReceipt
    {
        if (! $actor->hasPermission('purchases.post')) {
            abort(403);
        }
        $result = $this->idempotency->run($actor, 'receipt.post', $data['idempotency_key'], $data, GoodsReceipt::class, fn (int $id): GoodsReceipt => GoodsReceipt::query()->findOrFail($id), function () use ($actor, $data): GoodsReceipt {
            $lines = [];
            $total = 0;
            foreach ($data['items'] as $index => $item) {
                $unit = ProductUnit::query()->with('product')->whereKey($item['product_unit_id'])->where('is_active', true)->first();
                if (! $unit || ! $unit->product->is_active) {
                    throw ValidationException::withMessages(["items.{$index}" => 'Produk atau satuan tidak tersedia.']);
                }
                $quantity = Quantity::from((string) $item['quantity']);
                $cost = (int) $item['unit_cost_rupiah'];
                if ($quantity->scaled <= 0 || $cost <= 0) {
                    throw ValidationException::withMessages(["items.{$index}" => 'Jumlah dan harga beli harus lebih dari nol.']);
                }
                $lineSubtotal = $quantity->rupiahSubtotal($cost);
                $total += $lineSubtotal;
                $lines[] = compact('unit', 'quantity', 'cost', 'lineSubtotal');
            }
            $initial = (int) ($data['initial_payment_rupiah'] ?? 0);
            if ($initial > $total) {
                throw ValidationException::withMessages(['initial_payment_rupiah' => 'Pembayaran awal tidak boleh melebihi total.']);
            }
            if ($initial < $total && empty($data['due_on'])) {
                throw ValidationException::withMessages(['due_on' => 'Tanggal jatuh tempo wajib untuk penerimaan yang belum lunas.']);
            }
            $receipt = GoodsReceipt::query()->create(['number' => $this->numbers->next('PB', now()), 'supplier_id' => $data['supplier_id'], 'supplier_document_number' => $data['supplier_document_number'] ?? null, 'status' => GoodsReceiptStatus::Posted, 'received_on' => $data['received_on'], 'subtotal_rupiah' => $total, 'total_rupiah' => $total, 'payment_state' => $initial === 0 ? 'unpaid' : ($initial < $total ? 'partially_paid' : 'paid'), 'initial_payment_rupiah' => $initial, 'due_on' => $data['due_on'] ?? null, 'notes' => $data['notes'] ?? null, 'created_by' => $actor->id, 'posted_by' => $actor->id, 'posted_at' => now(), 'idempotency_key' => $data['idempotency_key']]);
            foreach ($lines as $line) {
                $unit = $line['unit'];
                $quantity = $line['quantity'];
                $base = $quantity->multiply(Quantity::from((string) $unit->conversion_to_base));
                $receipt->items()->create(['product_id' => $unit->product_id, 'product_unit_id' => $unit->id, 'product_name' => $unit->product->name, 'sku' => $unit->product->sku, 'unit_label' => $unit->label, 'conversion_to_base' => $unit->conversion_to_base, 'quantity' => $quantity->decimal(), 'base_quantity' => $base->decimal(), 'unit_cost_rupiah' => $line['cost'], 'subtotal_rupiah' => $line['lineSubtotal']]);
                $this->ledger->post($unit->product, StockMovementType::PurchaseReceipt->value, $base, $receipt, $actor);
            }
            if ($initial < $total) {
                $balance = $total - $initial;
                $payable = $receipt->payable()->create(['number' => $this->numbers->next('KW', now()), 'supplier_id' => $data['supplier_id'], 'status' => $initial > 0 ? PayableStatus::PartiallyPaid : PayableStatus::Unpaid, 'principal_rupiah' => $total, 'paid_rupiah' => $initial, 'balance_rupiah' => $balance, 'due_on' => $data['due_on']]);
                if ($initial > 0) {
                    $payment = $payable->payments()->create(['number' => $this->numbers->next('BY', now()), 'paid_on' => $data['received_on'], 'amount_rupiah' => $initial, 'method' => $data['payment_method'] ?? 'cash', 'status' => 'posted', 'created_by' => $actor->id, 'idempotency_key' => (string) Str::uuid()]);
                    $this->audit->record('payable.payment_posted', $actor, $payment, [], ['number' => $payment->number, 'amount_rupiah' => $initial], ['payable' => $payable->number, 'source' => 'receipt_initial_payment']);
                }
            }
            $this->audit->record('receipt.posted', $actor, $receipt, [], ['number' => $receipt->number, 'total_rupiah' => $total]);

            return $receipt;
        });

        return $result;
    }
}
