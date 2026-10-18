<?php

namespace App\Http\Controllers;

use App\Actions\Inventory\PostStockAdjustment;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()->with(['category', 'baseUnit', 'stockBalance'])->search($request->input('q'))->orderBy('name')->paginate(20)->withQueryString();

        return view('pages.inventory.index', compact('products'));
    }

    public function movements(Request $request): View
    {
        $movements = StockMovement::query()->with(['product.baseUnit', 'actor'])->when($request->filled('product'), fn ($q) => $q->where('product_id', $request->input('product')))->latest('occurred_at')->paginate(30)->withQueryString();

        return view('pages.inventory.movements', compact('movements'));
    }

    public function createAdjustment(): View
    {
        return view('pages.inventory.adjustment-form', ['products' => Product::with(['stockBalance', 'baseUnit'])->where('is_active', true)->orderBy('name')->get(), 'idempotencyKey' => (string) Str::uuid()]);
    }

    public function storeAdjustment(Request $request, PostStockAdjustment $action): RedirectResponse
    {
        $data = $request->validate(['product_id' => 'required|exists:products,id', 'physical_quantity' => 'required|decimal:0,4|min:0', 'reason_code' => 'required|in:physical_count,damaged,lost,expired,input_correction,opening_balance', 'notes' => 'required|string|min:5|max:500', 'idempotency_key' => 'required|uuid']);
        $action->handle($request->user(), $data);

        return redirect()->route('inventory.index')->with('status', 'Penyesuaian stok berhasil diposting.');
    }
}
