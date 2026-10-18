<?php

namespace App\Domain\Identity\Enums;

enum Role: string
{
    case Owner = 'owner';
    case InventoryAdmin = 'inventory_admin';
    case Cashier = 'cashier';
    case Auditor = 'auditor';

    public function label(): string
    {
        return match ($this) {
            self::Owner => 'Pemilik',
            self::InventoryAdmin => 'Petugas Persediaan',
            self::Cashier => 'Kasir',
            self::Auditor => 'Auditor',
        };
    }

    /** @return list<string> */
    public function permissions(): array
    {
        $operational = ['dashboard.view_operational', 'products.view', 'sales.create'];

        return match ($this) {
            self::Owner => ['*'],
            self::Cashier => [...$operational, 'sales.view_own'],
            self::InventoryAdmin => [...$operational, 'sales.view_all', 'products.create', 'products.update', 'products.disable', 'product_units.manage', 'prices.manage_retail', 'prices.manage_wholesale', 'purchases.view', 'purchases.create', 'purchases.post', 'stock.view', 'suppliers.manage', 'payables.view', 'payables.manage', 'payable_payments.post', 'documents.upload', 'documents.view', 'reports.sales', 'reports.stock', 'reports.payables'],
            self::Auditor => ['dashboard.view_owner', 'products.view', 'sales.view_all', 'purchases.view', 'stock.view', 'payables.view', 'documents.view', 'reports.sales', 'reports.stock', 'reports.payables', 'reports.export', 'audit.view', 'settings.view'],
        };
    }

    public function allows(string $permission): bool
    {
        $permissions = $this->permissions();

        return in_array('*', $permissions, true) || in_array($permission, $permissions, true);
    }
}
