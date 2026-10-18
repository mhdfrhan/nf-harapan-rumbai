<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('units', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 60);
            $table->string('dimension', 20)->default('count');
            $table->unsignedTinyInteger('decimal_scale')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('base_unit_id')->constrained('units')->restrictOnDelete();
            $table->string('sku', 64)->unique();
            $table->string('name', 180)->index();
            $table->text('description')->nullable();
            $table->string('barcode', 100)->nullable()->unique();
            $table->decimal('low_stock_threshold', 18, 4)->default(0);
            $table->boolean('allows_negative_stock')->default(false);
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->index(['category_id', 'is_active']);
        });

        Schema::create('product_units', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->restrictOnDelete();
            $table->string('label', 80);
            $table->decimal('conversion_to_base', 18, 4);
            $table->string('barcode', 100)->nullable()->unique();
            $table->boolean('is_default_sale_unit')->default(false);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->unique(['product_id', 'unit_id', 'label']);
        });

        Schema::create('product_prices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_unit_id')->constrained()->cascadeOnDelete();
            $table->string('price_type', 20);
            $table->unsignedBigInteger('amount_rupiah');
            $table->decimal('minimum_quantity', 18, 4)->default(1);
            $table->timestamp('effective_from')->nullable();
            $table->timestamp('effective_until')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->index(['product_unit_id', 'price_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_prices');
        Schema::dropIfExists('product_units');
        Schema::dropIfExists('products');
        Schema::dropIfExists('units');
        Schema::dropIfExists('categories');
    }
};
