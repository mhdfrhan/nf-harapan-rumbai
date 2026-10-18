<?php

namespace Database\Seeders;

use App\Domain\Catalog\Enums\PriceType;
use App\Domain\Identity\Enums\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockBalance;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use LogicException;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $configuredPassword = config('store.seed_owner_password');
        $password = is_string($configuredPassword) && $configuredPassword !== '' ? $configuredPassword : null;
        if (app()->isProduction() && blank($password)) {
            throw new LogicException('SEED_OWNER_PASSWORD wajib diisi ketika melakukan seed di production.');
        }

        $owner = User::query()->updateOrCreate(['email' => 'emi@nfharapan.local'], [
            'username' => 'owner',
            'name' => 'Ibu Emi Erawati',
            'email' => 'emi@nfharapan.local',
            'password' => Hash::make($password ?? 'password'),
            'role' => Role::Owner,
            'is_active' => true,
            'force_password_change' => false,
        ]);

        $units = collect([
            ['code' => 'PCS', 'name' => 'Buah/Pak', 'dimension' => 'count', 'decimal_scale' => 0],
            ['code' => 'KG', 'name' => 'Kilogram', 'dimension' => 'mass', 'decimal_scale' => 3],
            ['code' => 'LTR', 'name' => 'Liter', 'dimension' => 'volume', 'decimal_scale' => 3],
        ])->mapWithKeys(fn (array $unit) => [$unit['code'] => Unit::query()->updateOrCreate(['code' => $unit['code']], [...$unit, 'is_active' => true])]);

        if (! app()->isProduction()) {
            $category = Category::query()->updateOrCreate(['slug' => 'sembako'], ['name' => 'Sembako', 'is_active' => true]);
            $product = Product::query()->firstOrNew(['sku' => 'SMB-001']);
            $product->fill(['category_id' => $category->id, 'base_unit_id' => $units['KG']->id, 'name' => 'Gula Pasir', 'description' => 'Data contoh lokal untuk verifikasi alur.', 'low_stock_threshold' => '10.0000', 'allows_negative_stock' => false, 'is_active' => true])->save();
            $saleUnit = $product->saleUnits()->updateOrCreate(['unit_id' => $units['KG']->id, 'label' => 'Kilogram'], ['conversion_to_base' => '1.0000', 'is_default_sale_unit' => true, 'is_active' => true]);
            $saleUnit->prices()->updateOrCreate(['price_type' => PriceType::Retail, 'minimum_quantity' => '1.0000'], ['amount_rupiah' => 18000, 'effective_from' => now()->startOfDay(), 'is_active' => true]);
            $saleUnit->prices()->updateOrCreate(['price_type' => PriceType::Wholesale, 'minimum_quantity' => '10.0000'], ['amount_rupiah' => 17000, 'effective_from' => now()->startOfDay(), 'is_active' => true]);
            $movement = StockMovement::query()->firstOrCreate(['product_id' => $product->id, 'movement_type' => 'opening_balance', 'reference_type' => 'Seeder', 'reference_id' => 0], ['quantity_delta' => '100.0000', 'balance_after' => '100.0000', 'occurred_at' => now(), 'actor_id' => $owner->id, 'reason_code' => 'local_sample', 'notes' => 'Saldo awal data contoh lokal.']);
            StockBalance::query()->updateOrCreate(['product_id' => $product->id], ['quantity_on_hand' => '100.0000', 'version' => 1, 'last_movement_id' => $movement->id]);
        }
    }
}
