<?php

namespace Tests\Feature;

use App\Domain\Identity\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UiRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, array{string}> */
    public static function ownerRoutes(): array
    {
        return [
            'dashboard' => ['/dashboard'], 'pos' => ['/pos'], 'sales' => ['/sales'],
            'products' => ['/catalog/products'], 'create product' => ['/catalog/products/create'],
            'categories' => ['/catalog/categories'], 'units' => ['/catalog/units'],
            'inventory' => ['/inventory'], 'movements' => ['/inventory/movements'],
            'adjustment' => ['/inventory/adjustments/create'], 'receipts' => ['/inventory/receipts'],
            'create receipt' => ['/inventory/receipts/create'], 'suppliers' => ['/suppliers'],
            'payables' => ['/payables'], 'sales report' => ['/reports/sales'],
            'inventory report' => ['/reports/inventory'], 'purchases report' => ['/reports/purchases'],
            'payables report' => ['/reports/payables'], 'users' => ['/admin/users'],
            'audit' => ['/admin/audit'], 'store settings' => ['/settings/store'],
        ];
    }

    #[DataProvider('ownerRoutes')]
    public function test_owner_can_render_authorized_ui_routes(string $uri): void
    {
        $owner = User::factory()->create(['role' => Role::Owner, 'force_password_change' => false]);

        $this->actingAs($owner)->get($uri)->assertOk();
    }

    public function test_cashier_is_forbidden_from_owner_data(): void
    {
        $cashier = User::factory()->create(['role' => Role::Cashier]);

        foreach (['/admin/users', '/admin/audit', '/payables', '/reports/sales', '/inventory'] as $uri) {
            $this->actingAs($cashier)->get($uri)->assertForbidden();
        }
    }
}
