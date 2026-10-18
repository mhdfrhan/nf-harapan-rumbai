<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('name', 180)->index();
            $table->string('phone', 40)->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('stock_balances', function (Blueprint $table): void {
            $table->foreignId('product_id')->primary()->constrained()->cascadeOnDelete();
            $table->decimal('quantity_on_hand', 18, 4)->default(0);
            $table->unsignedBigInteger('version')->default(0);
            $table->unsignedBigInteger('last_movement_id')->nullable();
            $table->timestamps();
        });

        Schema::create('sales', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('number', 32)->unique();
            $table->string('status', 20)->index();
            $table->timestamp('sold_at')->index();
            $table->foreignId('cashier_id')->constrained('users')->restrictOnDelete();
            $table->unsignedBigInteger('subtotal_rupiah');
            $table->unsignedBigInteger('discount_rupiah')->default(0);
            $table->unsignedBigInteger('total_rupiah');
            $table->text('notes')->nullable();
            $table->uuid('idempotency_key')->unique();
            $table->timestamp('posted_at')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('void_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_unit_id')->constrained()->restrictOnDelete();
            $table->string('product_name', 180);
            $table->string('sku', 64);
            $table->string('unit_label', 80);
            $table->decimal('conversion_to_base', 18, 4);
            $table->decimal('quantity', 18, 4);
            $table->decimal('base_quantity', 18, 4);
            $table->string('price_type', 20);
            $table->unsignedBigInteger('unit_price_rupiah');
            $table->unsignedBigInteger('discount_rupiah')->default(0);
            $table->unsignedBigInteger('subtotal_rupiah');
            $table->timestamps();
        });

        Schema::create('sale_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->string('method', 20)->default('cash');
            $table->unsignedBigInteger('amount_rupiah');
            $table->unsignedBigInteger('received_rupiah');
            $table->unsignedBigInteger('change_rupiah')->default(0);
            $table->string('reference', 120)->nullable();
            $table->string('status', 20)->default('posted');
            $table->timestamps();
        });

        Schema::create('sale_voids', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sale_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('voided_by')->constrained('users')->restrictOnDelete();
            $table->string('reason_code', 40)->default('correction');
            $table->text('reason_text');
            $table->timestamp('created_at');
        });

        Schema::create('goods_receipts', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('number', 32)->unique();
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->string('supplier_document_number', 120)->nullable();
            $table->string('status', 20)->index();
            $table->date('received_on')->index();
            $table->unsignedBigInteger('subtotal_rupiah');
            $table->unsignedBigInteger('total_rupiah');
            $table->string('payment_state', 20);
            $table->unsignedBigInteger('initial_payment_rupiah')->default(0);
            $table->date('due_on')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('posted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('posted_at')->nullable();
            $table->uuid('idempotency_key')->unique();
            $table->timestamps();
            $table->unique(['supplier_id', 'supplier_document_number']);
        });

        Schema::create('goods_receipt_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('goods_receipt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_unit_id')->constrained()->restrictOnDelete();
            $table->string('product_name', 180);
            $table->string('sku', 64);
            $table->string('unit_label', 80);
            $table->decimal('conversion_to_base', 18, 4);
            $table->decimal('quantity', 18, 4);
            $table->decimal('base_quantity', 18, 4);
            $table->unsignedBigInteger('unit_cost_rupiah');
            $table->unsignedBigInteger('subtotal_rupiah');
            $table->timestamps();
        });

        Schema::create('payables', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('number', 32)->unique();
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->foreignId('goods_receipt_id')->nullable()->unique()->constrained()->restrictOnDelete();
            $table->string('status', 24)->index();
            $table->unsignedBigInteger('principal_rupiah');
            $table->unsignedBigInteger('paid_rupiah')->default(0);
            $table->unsignedBigInteger('balance_rupiah');
            $table->date('due_on')->nullable()->index();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payable_payments', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('number', 32)->unique();
            $table->foreignId('payable_id')->constrained()->restrictOnDelete();
            $table->date('paid_on')->index();
            $table->unsignedBigInteger('amount_rupiah');
            $table->string('method', 24);
            $table->string('reference', 120)->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('posted')->index();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('voided_at')->nullable();
            $table->text('void_reason')->nullable();
            $table->uuid('idempotency_key')->unique();
            $table->timestamps();
        });

        Schema::create('stock_adjustments', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('number', 32)->unique();
            $table->string('status', 20)->default('posted')->index();
            $table->timestamp('adjusted_at');
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('posted_by')->constrained('users')->restrictOnDelete();
            $table->string('reason_code', 40);
            $table->text('notes');
            $table->uuid('idempotency_key')->unique();
            $table->timestamps();
        });

        Schema::create('stock_adjustment_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('stock_adjustment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->decimal('system_quantity', 18, 4);
            $table->decimal('physical_quantity', 18, 4);
            $table->decimal('quantity_delta', 18, 4);
            $table->text('notes')->nullable();
        });

        Schema::create('stock_movements', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->string('movement_type', 32)->index();
            $table->decimal('quantity_delta', 18, 4);
            $table->decimal('balance_after', 18, 4);
            $table->string('reference_type', 50);
            $table->unsignedBigInteger('reference_id');
            $table->timestamp('occurred_at')->index();
            $table->foreignId('actor_id')->constrained('users')->restrictOnDelete();
            $table->string('reason_code', 40)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['product_id', 'occurred_at', 'id']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stock_adjustment_items');
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('payable_payments');
        Schema::dropIfExists('payables');
        Schema::dropIfExists('goods_receipt_items');
        Schema::dropIfExists('goods_receipts');
        Schema::dropIfExists('sale_voids');
        Schema::dropIfExists('sale_payments');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('stock_balances');
        Schema::dropIfExists('suppliers');
    }
};
