<?php

namespace App\Http\Controllers;

use App\Domain\Procurement\Enums\GoodsReceiptStatus;
use App\Domain\Sales\Enums\SaleStatus;
use App\Models\AuditLog;
use App\Models\GoodsReceipt;
use App\Models\Payable;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = request()->user();
        if (! $user->isOwner() && ! $user->hasPermission('dashboard.view_owner')) {
            $sales = Sale::query()->where('cashier_id', $user->id)->whereDate('sold_at', today())->latest('sold_at')->limit(8)->get();
            $staffMetrics = ['count' => $sales->count(), 'total' => $sales->where('status', SaleStatus::Posted)->sum('total_rupiah')];

            return view('dashboard-staff', compact('sales', 'staffMetrics'));
        }
        $metrics = [
            'sales_total' => Sale::query()->where('status', SaleStatus::Posted)->whereDate('sold_at', today())->sum('total_rupiah'),
            'sales_count' => Sale::query()->where('status', SaleStatus::Posted)->whereDate('sold_at', today())->count(),
            'receipts_total' => GoodsReceipt::query()->where('status', GoodsReceiptStatus::Posted)->whereDate('received_on', today())->sum('total_rupiah'),
            'payables_due' => Payable::query()->where('balance_rupiah', '>', 0)->whereDate('due_on', '<=', today()->addDays(7))->sum('balance_rupiah'),
            'low_stock' => Product::query()->whereHas('stockBalance', fn ($q) => $q->whereColumn('quantity_on_hand', '<=', 'products.low_stock_threshold'))->count(),
        ];
        $activities = AuditLog::query()->with('actor')->latest('occurred_at')->limit(8)->get();
        $dailySales = Sale::query()->where('status', SaleStatus::Posted)->whereBetween('sold_at', [today()->subDays(6)->startOfDay(), today()->endOfDay()])->selectRaw('DATE(sold_at) as sale_date, SUM(total_rupiah) as total')->groupBy('sale_date')->pluck('total', 'sale_date');
        $trend = collect(range(6, 0))->map(function (int $daysAgo) use ($dailySales): array {
            $date = today()->subDays($daysAgo);

            return ['label' => $date->translatedFormat('D'), 'total' => (int) ($dailySales[$date->toDateString()] ?? 0)];
        });

        return view('dashboard', compact('metrics', 'activities', 'trend'));
    }
}
