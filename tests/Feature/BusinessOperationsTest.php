<?php

namespace Tests\Feature;

use App\Actions\Inventory\PostStockAdjustment;
use App\Actions\Payables\RecordPayablePayment;
use App\Actions\Payables\VoidPayablePayment;
use App\Actions\Procurement\PostGoodsReceipt;
use App\Actions\Procurement\VoidGoodsReceipt;
use App\Actions\Sales\PostSale;
use App\Actions\Sales\VoidSale;
use App\Domain\Catalog\Enums\PriceType;
use App\Domain\Identity\Enums\Role;
use App\Domain\Inventory\Enums\StockMovementType;
use App\Domain\Sales\Enums\SaleStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockBalance;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use App\Services\DocumentStorage;
use App\Services\ProductPriceManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class BusinessOperationsTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private ProductUnit $productUnit;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = User::factory()->create(['role' => Role::Owner]);
        $category = Category::query()->create(['name' => 'Sembako', 'slug' => 'sembako', 'is_active' => true]);
        $unit = Unit::query()->create(['code' => 'PCS', 'name' => 'Pcs', 'dimension' => 'count', 'decimal_scale' => 0, 'is_active' => true]);
        $product = Product::query()->create(['category_id' => $category->id, 'base_unit_id' => $unit->id, 'sku' => 'SKU-001', 'name' => 'Produk Uji', 'low_stock_threshold' => 2, 'allows_negative_stock' => false, 'is_active' => true]);
        $this->productUnit = $product->saleUnits()->create(['unit_id' => $unit->id, 'label' => 'Pcs', 'conversion_to_base' => 1, 'is_default_sale_unit' => true, 'is_active' => true]);
        $this->productUnit->prices()->create(['price_type' => PriceType::Retail, 'amount_rupiah' => 10000, 'minimum_quantity' => 1, 'is_active' => true]);
        $this->productUnit->prices()->create(['price_type' => PriceType::Wholesale, 'amount_rupiah' => 9000, 'minimum_quantity' => 10, 'is_active' => true]);
        StockBalance::query()->create(['product_id' => $product->id, 'quantity_on_hand' => 20, 'version' => 0]);
    }

    public function test_sale_is_atomic_idempotent_and_uses_server_price(): void
    {
        $key = (string) Str::uuid();
        $payload = ['idempotency_key' => $key, 'items' => [['product_unit_id' => $this->productUnit->id, 'quantity' => 10]], 'payment_method' => 'cash', 'received_rupiah' => 100000];
        $action = app(PostSale::class);

        $sale = $action->handle($this->owner, $payload);
        $replayed = $action->handle($this->owner, $payload);

        self::assertTrue($sale->is($replayed));
        self::assertSame(90000, $sale->total_rupiah);
        self::assertSame(10000, $sale->payment->change_rupiah);
        self::assertSame('10.0000', $sale->fresh()->items->first()->base_quantity);
        self::assertSame('10.0000', $this->productUnit->product->stockBalance->fresh()->quantity_on_hand);
        $this->assertDatabaseCount('sales', 1);
        $this->assertDatabaseCount('stock_movements', 1);
    }

    public function test_insufficient_stock_rolls_back_entire_sale(): void
    {
        $this->expectException(ValidationException::class);
        try {
            app(PostSale::class)->handle($this->owner, ['idempotency_key' => (string) Str::uuid(), 'items' => [['product_unit_id' => $this->productUnit->id, 'quantity' => 21]], 'payment_method' => 'cash', 'received_rupiah' => 500000]);
        } finally {
            $this->assertDatabaseCount('sales', 0);
            $this->assertDatabaseCount('stock_movements', 0);
        }
    }

    public function test_void_reverses_stock_without_deleting_original_sale(): void
    {
        $sale = app(PostSale::class)->handle($this->owner, ['idempotency_key' => (string) Str::uuid(), 'items' => [['product_unit_id' => $this->productUnit->id, 'quantity' => 2]], 'payment_method' => 'cash', 'received_rupiah' => 20000]);

        $voided = app(VoidSale::class)->handle($this->owner, $sale, 'Kesalahan input transaksi kasir.', (string) Str::uuid());

        self::assertSame(SaleStatus::Voided, $voided->status);
        self::assertSame('20.0000', $this->productUnit->product->stockBalance->fresh()->quantity_on_hand);
        $this->assertDatabaseCount('sales', 1);
        $this->assertDatabaseCount('stock_movements', 2);
    }

    public function test_fractional_sale_unit_uses_exact_base_quantity(): void
    {
        $fractional = $this->productUnit->product->saleUnits()->create(['unit_id' => $this->productUnit->unit_id, 'label' => 'Seperempat', 'conversion_to_base' => '0.2500', 'is_default_sale_unit' => false, 'is_active' => true]);
        $fractional->prices()->create(['price_type' => PriceType::Retail, 'amount_rupiah' => 3000, 'minimum_quantity' => 1, 'is_active' => true]);

        $sale = app(PostSale::class)->handle($this->owner, ['idempotency_key' => (string) Str::uuid(), 'items' => [['product_unit_id' => $fractional->id, 'quantity' => 2]], 'payment_method' => 'cash', 'received_rupiah' => 10000]);

        self::assertSame('0.5000', $sale->items->first()->base_quantity);
        self::assertSame('19.5000', $fractional->product->stockBalance->fresh()->quantity_on_hand);
    }

    public function test_price_changes_preserve_history_and_only_one_price_remains_active(): void
    {
        $manager = app(ProductPriceManager::class);

        DB::transaction(fn () => $manager->set($this->productUnit, PriceType::Retail, 12000));
        DB::transaction(fn () => $manager->set($this->productUnit, PriceType::Retail, 12000));

        $retailPrices = $this->productUnit->prices()->where('price_type', PriceType::Retail)->orderBy('id')->get();
        self::assertCount(2, $retailPrices);
        self::assertFalse($retailPrices->first()->is_active);
        self::assertNotNull($retailPrices->first()->effective_until);
        self::assertTrue($retailPrices->last()->is_active);
        self::assertSame(12000, $retailPrices->last()->amount_rupiah);

        DB::transaction(fn () => $manager->set($this->productUnit, PriceType::Retail, null));
        self::assertSame(0, $this->productUnit->prices()->where('price_type', PriceType::Retail)->where('is_active', true)->count());
    }

    public function test_receipt_creates_stock_and_payable_then_payment_updates_balance(): void
    {
        $supplier = Supplier::query()->create(['name' => 'Pemasok Uji', 'is_active' => true]);
        $receipt = app(PostGoodsReceipt::class)->handle($this->owner, ['supplier_id' => $supplier->id, 'received_on' => today()->toDateString(), 'items' => [['product_unit_id' => $this->productUnit->id, 'quantity' => 5, 'unit_cost_rupiah' => 8000]], 'initial_payment_rupiah' => 10000, 'payment_method' => 'cash', 'due_on' => today()->addWeek()->toDateString(), 'idempotency_key' => (string) Str::uuid()]);

        self::assertSame(40000, $receipt->total_rupiah);
        self::assertSame(30000, $receipt->payable->balance_rupiah);
        self::assertSame('25.0000', $this->productUnit->product->stockBalance->fresh()->quantity_on_hand);

        $payment = app(RecordPayablePayment::class)->handle($this->owner, $receipt->payable, ['paid_on' => today()->toDateString(), 'amount_rupiah' => 30000, 'method' => 'transfer', 'reference' => 'TRX-001', 'idempotency_key' => (string) Str::uuid()]);
        self::assertSame(30000, $payment->amount_rupiah);
        self::assertSame(0, $receipt->payable->fresh()->balance_rupiah);

        app(VoidPayablePayment::class)->handle($this->owner, $receipt->payable->fresh(), $payment, 'Transfer tercatat pada kewajiban yang salah.', (string) Str::uuid());
        self::assertSame('voided', $payment->fresh()->status);
        self::assertSame(30000, $receipt->payable->fresh()->balance_rupiah);
    }

    public function test_owner_can_safely_void_receipt_and_its_initial_payment(): void
    {
        $supplier = Supplier::query()->create(['name' => 'Pemasok Void', 'is_active' => true]);
        StockMovement::query()->create(['product_id' => $this->productUnit->product_id, 'movement_type' => StockMovementType::OpeningBalance, 'quantity_delta' => '20.0000', 'balance_after' => '20.0000', 'reference_type' => class_basename(Product::class), 'reference_id' => $this->productUnit->product_id, 'occurred_at' => now(), 'actor_id' => $this->owner->id, 'reason_code' => 'test_opening_balance']);
        $receipt = app(PostGoodsReceipt::class)->handle($this->owner, ['supplier_id' => $supplier->id, 'received_on' => today()->toDateString(), 'items' => [['product_unit_id' => $this->productUnit->id, 'quantity' => 5, 'unit_cost_rupiah' => 8000]], 'initial_payment_rupiah' => 10000, 'payment_method' => 'cash', 'due_on' => today()->addWeek()->toDateString(), 'idempotency_key' => (string) Str::uuid()]);

        $voided = app(VoidGoodsReceipt::class)->handle($this->owner, $receipt, 'Nota pemasok tercatat dua kali.', (string) Str::uuid());

        self::assertSame('voided', $voided->status->value);
        self::assertSame('20.0000', $this->productUnit->product->stockBalance->fresh()->quantity_on_hand);
        self::assertSame('voided', $receipt->payable->fresh()->status->value);
        self::assertSame('voided', $receipt->payable->payments->first()->fresh()->status);
        $this->assertDatabaseCount('goods_receipts', 1);
        $this->assertDatabaseCount('stock_movements', 3);
        $this->artisan('store:reconcile --fail-on-difference')->assertSuccessful();
    }

    public function test_receipt_void_is_rejected_after_a_follow_up_payment(): void
    {
        $supplier = Supplier::query()->create(['name' => 'Pemasok Lanjut', 'is_active' => true]);
        $receipt = app(PostGoodsReceipt::class)->handle($this->owner, ['supplier_id' => $supplier->id, 'received_on' => today()->toDateString(), 'items' => [['product_unit_id' => $this->productUnit->id, 'quantity' => 5, 'unit_cost_rupiah' => 8000]], 'initial_payment_rupiah' => 0, 'due_on' => today()->addWeek()->toDateString(), 'idempotency_key' => (string) Str::uuid()]);
        app(RecordPayablePayment::class)->handle($this->owner, $receipt->payable, ['paid_on' => today()->toDateString(), 'amount_rupiah' => 5000, 'method' => 'transfer', 'idempotency_key' => (string) Str::uuid()]);

        $this->expectException(ValidationException::class);
        try {
            app(VoidGoodsReceipt::class)->handle($this->owner, $receipt, 'Nota pemasok tercatat dua kali.', (string) Str::uuid());
        } finally {
            self::assertSame('25.0000', $this->productUnit->product->stockBalance->fresh()->quantity_on_hand);
            self::assertSame('posted', $receipt->fresh()->status->value);
        }
    }

    public function test_cashier_cannot_post_procurement(): void
    {
        $cashier = User::factory()->create(['role' => Role::Cashier]);
        $supplier = Supplier::query()->create(['name' => 'Pemasok Uji', 'is_active' => true]);

        $this->expectException(HttpException::class);
        app(PostGoodsReceipt::class)->handle($cashier, ['supplier_id' => $supplier->id, 'received_on' => today()->toDateString(), 'items' => [], 'idempotency_key' => (string) Str::uuid()]);
    }

    public function test_opening_balance_uses_a_distinct_movement_and_cannot_be_repeated(): void
    {
        $this->productUnit->product->stockBalance->update(['quantity_on_hand' => '0.0000']);
        $payload = ['product_id' => $this->productUnit->product_id, 'physical_quantity' => 15, 'reason_code' => 'opening_balance', 'notes' => 'Stok awal hasil perhitungan fisik.', 'idempotency_key' => (string) Str::uuid()];

        app(PostStockAdjustment::class)->handle($this->owner, $payload);

        $this->assertDatabaseHas('stock_movements', ['product_id' => $this->productUnit->product_id, 'movement_type' => 'opening_balance', 'quantity_delta' => 15]);

        $this->expectException(ValidationException::class);
        app(PostStockAdjustment::class)->handle($this->owner, [...$payload, 'physical_quantity' => 16, 'idempotency_key' => (string) Str::uuid()]);
    }

    public function test_private_document_requires_document_permission(): void
    {
        Storage::fake('private');
        $document = app(DocumentStorage::class)->store(UploadedFile::fake()->image('bukti.jpg'), $this->owner, 'receipt_proof');
        $cashier = User::factory()->create(['role' => Role::Cashier]);
        $auditor = User::factory()->create(['role' => Role::Auditor]);

        $this->actingAs($cashier)->get(route('documents.download', $document))->assertForbidden();
        $this->actingAs($auditor)->get(route('documents.download', $document))->assertOk()->assertHeaderContains('cache-control', 'private')->assertHeaderContains('cache-control', 'no-store');
    }

    public function test_temporary_password_blocks_business_routes(): void
    {
        $user = User::factory()->create(['role' => Role::Cashier, 'force_password_change' => true]);

        $this->actingAs($user)->get(route('pos.index'))->assertRedirect(route('security.edit'));
    }
}
