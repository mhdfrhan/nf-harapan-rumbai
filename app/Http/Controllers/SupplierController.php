<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $suppliers = Supplier::query()
            ->withSum(['payables as open_balance' => fn ($query) => $query->where('balance_rupiah', '>', 0)], 'balance_rupiah')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = '%'.addcslashes($request->string('search')->toString(), '%_\\').'%';
                $query->where(fn ($nested) => $nested->where('name', 'like', $search)->orWhere('phone', 'like', $search));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->string('status')->toString() === 'active'))
            ->orderBy('name')
            ->paginate(18)
            ->withQueryString();

        return view('pages.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request, AuditLogger $audit): RedirectResponse
    {
        abort_unless($request->user()->hasPermission('suppliers.manage'), 403);
        $data = $request->validate(['name' => 'required|string|max:180', 'phone' => 'nullable|string|max:40', 'address' => 'nullable|string|max:1000', 'notes' => 'nullable|string|max:1000']);
        $supplier = Supplier::create([...$data, 'is_active' => true]);
        $audit->record('supplier.created', $request->user(), $supplier);

        return redirect()->route('suppliers.show', $supplier)->with('status', 'Pemasok berhasil ditambahkan.');
    }

    public function update(Request $request, Supplier $supplier, AuditLogger $audit): RedirectResponse
    {
        $data = $request->validate(['name' => 'required|string|max:180', 'phone' => 'nullable|string|max:40', 'address' => 'nullable|string|max:1000', 'notes' => 'nullable|string|max:1000', 'is_active' => 'required|boolean']);
        $before = $supplier->only(['name', 'phone', 'address', 'notes', 'is_active']);
        $supplier->update($data);
        $audit->record('supplier.updated', $request->user(), $supplier, $before, $supplier->only(['name', 'phone', 'address', 'notes', 'is_active']));

        return back()->with('status', 'Data pemasok berhasil diperbarui.');
    }

    public function show(Supplier $supplier): View
    {
        $supplier->load(['receipts' => fn ($q) => $q->latest('received_on')->limit(20), 'payables' => fn ($q) => $q->where('balance_rupiah', '>', 0)]);

        return view('pages.suppliers.show', compact('supplier'));
    }
}
