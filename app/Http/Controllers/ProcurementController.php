<?php

namespace App\Http\Controllers;

use App\Actions\Procurement\PostGoodsReceipt;
use App\Actions\Procurement\VoidGoodsReceipt;
use App\Models\Document;
use App\Models\GoodsReceipt;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\DocumentStorage;
use App\Support\RupiahInput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProcurementController extends Controller
{
    public function index(): View
    {
        return view('pages.receipts.index', ['receipts' => GoodsReceipt::with('supplier')->withCount('items')->latest('received_on')->paginate(20)]);
    }

    public function create(): View
    {
        return view('pages.receipts.form', ['suppliers' => Supplier::where('is_active', true)->orderBy('name')->get(), 'products' => Product::with(['saleUnits' => fn ($q) => $q->where('is_active', true)])->where('is_active', true)->orderBy('name')->get(), 'idempotencyKey' => (string) Str::uuid()]);
    }

    public function store(Request $request, PostGoodsReceipt $action, DocumentStorage $documents): RedirectResponse
    {
        $data = $request->validate(['supplier_id' => 'required|exists:suppliers,id', 'received_on' => 'required|date', 'supplier_document_number' => 'nullable|string|max:120', 'items' => 'required|array|min:1|max:100', 'items.*.product_unit_id' => 'required|exists:product_units,id', 'items.*.quantity' => 'required|decimal:0,4|gt:0', 'items.*.unit_cost_rupiah' => 'required', 'initial_payment_rupiah' => 'nullable', 'payment_method' => 'nullable|in:cash,transfer', 'due_on' => 'nullable|date|after_or_equal:received_on', 'notes' => 'nullable|string|max:1000', 'proof' => 'nullable|file|max:8192|mimetypes:image/jpeg,image/png,application/pdf', 'idempotency_key' => 'required|uuid']);
        foreach ($data['items'] as &$item) {
            $item['unit_cost_rupiah'] = RupiahInput::parse($item['unit_cost_rupiah'], 'items.unit_cost_rupiah');
        } unset($item);
        $data['initial_payment_rupiah'] = empty($data['initial_payment_rupiah']) ? 0 : RupiahInput::parse($data['initial_payment_rupiah'], 'initial_payment_rupiah');
        if ($data['initial_payment_rupiah'] > 0 && ! $request->hasFile('proof')) {
            throw ValidationException::withMessages(['proof' => 'Bukti wajib dilampirkan ketika ada pembayaran awal.']);
        }
        unset($data['proof']);
        $receipt = DB::transaction(function () use ($action, $request, $data, $documents) {
            $receipt = $action->handle($request->user(), $data);
            $hasProof = Document::query()->where('attachable_type', class_basename($receipt))->where('attachable_id', $receipt->id)->where('category', 'receipt_proof')->exists();
            if ($request->hasFile('proof') && ! $hasProof) {
                $documents->store($request->file('proof'), $request->user(), 'receipt_proof', $receipt);
            }

            return $receipt;
        });

        return redirect()->route('receipts.show', $receipt)->with('status', "Penerimaan {$receipt->number} berhasil diposting.");
    }

    public function show(GoodsReceipt $receipt): View
    {
        $receipt->load(['supplier', 'items', 'documents', 'payable.payments']);

        return view('pages.receipts.show', compact('receipt'));
    }

    public function void(Request $request, GoodsReceipt $receipt, VoidGoodsReceipt $action): RedirectResponse
    {
        $data = $request->validate(['reason' => ['required', 'string', 'min:10', 'max:1000'], 'idempotency_key' => ['required', 'uuid']]);
        $action->handle($request->user(), $receipt, $data['reason'], $data['idempotency_key']);

        return redirect()->route('receipts.show', $receipt)->with('status', "Penerimaan {$receipt->number} berhasil dibatalkan dan stok telah dibalik.");
    }
}
