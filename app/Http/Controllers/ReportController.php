<?php

namespace App\Http\Controllers;

use App\Domain\Payables\Enums\PayableStatus;
use App\Domain\Procurement\Enums\GoodsReceiptStatus;
use App\Domain\Sales\Enums\SaleStatus;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\Payable;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Services\AuditLogger;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function sales(Request $r): View|StreamedResponse
    {
        $from = $r->filled('from') ? $r->date('from') : today()->startOfMonth();
        $to = $r->filled('to') ? $r->date('to') : today();
        abort_if(! $from || ! $to || $from->greaterThan($to), 422, 'Rentang tanggal laporan tidak valid.');
        $query = Sale::with(['cashier', 'items'])->where('status', SaleStatus::Posted)->whereBetween('sold_at', [$from->startOfDay(), $to->endOfDay()])
            ->when($r->filled('cashier_id'), fn ($query) => $query->where('cashier_id', $r->integer('cashier_id')))
            ->when($r->filled('product_id'), fn ($query) => $query->whereHas('items', fn ($items) => $items->where('product_id', $r->integer('product_id'))))
            ->when(in_array($r->input('price_type'), ['retail', 'wholesale'], true), fn ($query) => $query->whereHas('items', fn ($items) => $items->where('price_type', $r->input('price_type'))));

        $totalSales = (int) (clone $query)->sum('total_rupiah');
        $salesCount = (clone $query)->count();
        $avgSale = $salesCount > 0 ? (int) round($totalSales / $salesCount) : 0;

        // Hitung estimasi modal (HPP) berdasarkan riwayat harga beli terbaru
        $receiptCosts = GoodsReceiptItem::query()
            ->orderByDesc('id')
            ->get()
            ->groupBy('product_id')
            ->map(fn ($items) => $items->first()->conversion_to_base > 0
                ? (float) $items->first()->unit_cost_rupiah / (float) $items->first()->conversion_to_base
                : 0.0);

        $salesForCost = (clone $query)->get();
        $totalCost = 0.0;
        foreach ($salesForCost as $sale) {
            foreach ($sale->items as $item) {
                $unitCost = $receiptCosts->get($item->product_id)
                    ?? (($item->unit_price_rupiah * 0.75) / (max(0.0001, (float) $item->conversion_to_base)));
                $totalCost += (float) $item->base_quantity * $unitCost;
            }
        }
        $totalCost = (int) round($totalCost);
        $grossProfit = $totalSales - $totalCost;
        $profitMargin = $totalSales > 0 ? round(($grossProfit / $totalSales) * 100, 1) : 0;

        // Data grafik harian dalam rentang tanggal
        $dailySales = (clone $query)
            ->selectRaw('DATE(sold_at) as sale_date, SUM(total_rupiah) as total, COUNT(id) as count')
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get()
            ->keyBy('sale_date');

        $periodDays = min(31, $from->diffInDays($to) + 1);
        $chartPoints = [];
        for ($i = 0; $i < $periodDays; $i++) {
            $day = $from->copy()->addDays($i);
            $dateStr = $day->toDateString();
            $record = $dailySales->get($dateStr);
            $dayTotal = $record ? (int) $record->total : 0;
            $dayCount = $record ? (int) $record->count : 0;
            $chartPoints[] = [
                'date' => $dateStr,
                'label' => $day->translatedFormat('d M'),
                'dayName' => $day->translatedFormat('D'),
                'total' => $dayTotal,
                'count' => $dayCount,
            ];
        }

        $metrics = [
            'total_sales' => $totalSales,
            'total_cost' => $totalCost,
            'gross_profit' => $grossProfit,
            'profit_margin' => $profitMargin,
            'sales_count' => $salesCount,
            'avg_sale' => $avgSale,
            'chart_points' => $chartPoints,
            'max_daily_total' => max(1, collect($chartPoints)->max('total') ?? 1),
        ];

        return $this->renderOrCsv($r, 'pages.reports.sales', $query, ['number', 'sold_at', 'cashier.name', 'total_rupiah'], [
            'cashiers' => User::query()->whereHas('sales')->orderBy('name')->get(),
            'products' => Product::query()->orderBy('name')->get(),
            'metrics' => $metrics,
        ]);
    }

    public function inventory(Request $r): View|StreamedResponse
    {
        $query = Product::with(['baseUnit', 'stockBalance'])->orderBy('name');

        return $this->renderOrCsv($r, 'pages.reports.inventory', $query, ['sku', 'name', 'stockBalance.quantity_on_hand', 'baseUnit.code']);
    }

    public function purchases(Request $r): View|StreamedResponse
    {
        [$from, $to] = $this->dateRange($r);
        $query = GoodsReceipt::with('supplier')->where('status', GoodsReceiptStatus::Posted)->whereBetween('received_on', [$from->toDateString(), $to->toDateString()])->latest('received_on');

        return $this->renderOrCsv($r, 'pages.reports.purchases', $query, ['number', 'received_on', 'supplier.name', 'total_rupiah', 'payment_state']);
    }

    public function payables(Request $r): View|StreamedResponse
    {
        [$from, $to] = $this->dateRange($r);
        $query = Payable::with('supplier')->where('status', '!=', PayableStatus::Voided)->whereBetween('due_on', [$from->toDateString(), $to->toDateString()])->latest('due_on');

        return $this->renderOrCsv($r, 'pages.reports.payables', $query, ['number', 'supplier.name', 'principal_rupiah', 'paid_rupiah', 'balance_rupiah', 'due_on', 'status']);
    }

    /**
     * @template TModel of Model
     *
     * @param  view-string  $view
     * @param  Builder<TModel>  $query
     * @param  list<string>  $fields
     * @param  array<string, mixed>  $viewData
     */
    private function renderOrCsv(Request $r, string $view, Builder $query, array $fields, array $viewData = []): View|StreamedResponse
    {
        if ($r->boolean('export')) {
            abort_unless($r->user()->hasPermission('reports.export'), 403);
            app(AuditLogger::class)->record('report.exported', $r->user(), null, [], [], ['report' => $view, 'filters' => $r->except('export')]);

            return new StreamedResponse(function () use ($query, $fields): void {
                $out = fopen('php://output', 'wb');
                if ($out === false) {
                    throw new \RuntimeException('Stream laporan tidak dapat dibuka.');
                }
                fwrite($out, "\xEF\xBB\xBF");
                fputcsv($out, $fields);
                $query->chunk(500, function ($rows) use ($out, $fields): void {
                    foreach ($rows as $row) {
                        fputcsv($out, array_map(fn ($field) => $this->csvSafe(data_get($row, $field)), $fields));
                    }
                });
                fclose($out);
            }, 200, ['Content-Type' => 'text/csv; charset=UTF-8', 'Content-Disposition' => 'attachment; filename="laporan-'.now()->format('Ymd-His').'.csv"']);
        }
        $rows = $query->paginate(30)->withQueryString();

        return view($view, ['rows' => $rows, ...$viewData]);
    }

    private function csvSafe(mixed $value): string
    {
        $string = (string) ($value instanceof \BackedEnum ? $value->value : $value);

        return preg_match('/^\s*[=+\-@]/u', $string) ? "'{$string}" : $string;
    }

    /** @return array{CarbonInterface, CarbonInterface} */
    private function dateRange(Request $request): array
    {
        $from = $request->filled('from') ? $request->date('from') : today()->startOfMonth();
        $to = $request->filled('to') ? $request->date('to') : today();
        abort_if(! $from || ! $to || $from->greaterThan($to), 422, 'Rentang tanggal laporan tidak valid.');

        return [$from, $to];
    }
}
