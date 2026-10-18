<?php

namespace App\Http\Controllers;

use App\Actions\Sales\PostSale;
use App\Actions\Sales\VoidSale;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Support\RupiahInput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function pos(): View
    {
        $products = Product::query()->with(['stockBalance', 'saleUnits' => fn ($q) => $q->where('is_active', true)->with(['prices' => fn ($p) => $p->where('is_active', true)])])->where('is_active', true)->orderBy('name')->get();

        return view('pages.pos.index', ['products' => $products, 'categories' => Category::where('is_active', true)->get(), 'idempotencyKey' => (string) Str::uuid()]);
    }

    public function store(Request $request, PostSale $action): RedirectResponse
    {
        $data = $request->validate(['idempotency_key' => 'required|uuid', 'items' => 'required|array|min:1|max:100', 'items.*.product_unit_id' => 'required|integer|exists:product_units,id', 'items.*.quantity' => 'required|decimal:0,4|gt:0', 'payment_method' => 'required|in:cash,transfer,qris', 'received_rupiah' => 'required', 'notes' => 'nullable|string|max:500']);
        $data['received_rupiah'] = RupiahInput::parse($data['received_rupiah'], 'received_rupiah');
        $sale = $action->handle($request->user(), $data);

        return redirect()->route('sales.show', $sale)->with('status', "Penjualan {$sale->number} berhasil disimpan.");
    }

    public function index(Request $request): View
    {
        $query = Sale::query()->with('cashier')->withCount('items');
        if (! $request->user()->hasPermission('sales.view_all')) {
            $query->where('cashier_id', $request->user()->id)->whereDate('sold_at', today());
        }
        $sales = $query->when($request->filled('q'), fn ($q) => $q->where('number', 'like', '%'.$request->input('q').'%'))->latest('sold_at')->paginate(20)->withQueryString();

        return view('pages.sales.index', compact('sales'));
    }

    public function show(Request $request, Sale $sale): View
    {
        abort_unless($request->user()->hasPermission('sales.view_all') || ($request->user()->hasPermission('sales.view_own') && $sale->cashier_id === $request->user()->id), 403);
        $sale->load(['items', 'payment', 'cashier', 'voidRecord.actor']);

        return view('pages.sales.show', compact('sale'));
    }

    public function void(Request $request, Sale $sale, VoidSale $action): RedirectResponse
    {
        $data = $request->validate(['reason' => 'required|string|min:10|max:500', 'idempotency_key' => 'required|uuid']);
        $action->handle($request->user(), $sale, $data['reason'], $data['idempotency_key']);

        return back()->with('status', 'Penjualan dibatalkan dan stok telah dikembalikan.');
    }
}
