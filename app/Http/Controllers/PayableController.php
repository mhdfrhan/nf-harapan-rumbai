<?php

namespace App\Http\Controllers;

use App\Actions\Payables\RecordPayablePayment;
use App\Actions\Payables\VoidPayablePayment;
use App\Models\Document;
use App\Models\Payable;
use App\Models\PayablePayment;
use App\Models\Supplier;
use App\Services\DocumentStorage;
use App\Support\RupiahInput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PayableController extends Controller
{
    public function index(Request $request): View
    {
        $payables = Payable::with(['supplier', 'receipt'])
            ->when($request->filled('supplier_id'), fn ($q) => $q->where('supplier_id', $request->integer('supplier_id')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('due_on', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('due_on', '<=', $request->date('to')))
            ->when($request->filled('status'), function ($query) use ($request): void {
                match ($request->string('status')->toString()) {
                    'overdue' => $query->where('balance_rupiah', '>', 0)->whereDate('due_on', '<', today()),
                    'paid' => $query->where('balance_rupiah', 0),
                    'partially_paid' => $query->where('paid_rupiah', '>', 0)->where('balance_rupiah', '>', 0),
                    'unpaid' => $query->where('paid_rupiah', 0)->where('balance_rupiah', '>', 0),
                    default => null,
                };
            })->latest('due_on')->paginate(20)->withQueryString();

        return view('pages.payables.index', ['payables' => $payables, 'suppliers' => Supplier::query()->orderBy('name')->get()]);
    }

    public function show(Payable $payable): View
    {
        $payable->load(['supplier', 'receipt.documents', 'payments.creator', 'payments.documents']);

        return view('pages.payables.show', compact('payable'));
    }

    public function createPayment(Payable $payable): View
    {
        abort_if($payable->balance_rupiah === 0, 404);

        return view('pages.payables.payment-form', ['payable' => $payable->load('supplier'), 'idempotencyKey' => (string) Str::uuid()]);
    }

    public function storePayment(Request $request, Payable $payable, RecordPayablePayment $action, DocumentStorage $documents): RedirectResponse
    {
        $data = $request->validate(['paid_on' => 'required|date', 'amount_rupiah' => 'required', 'method' => 'required|in:cash,transfer', 'reference' => 'nullable|string|max:120', 'notes' => 'nullable|string|max:1000', 'proof' => 'required|file|max:8192|mimetypes:image/jpeg,image/png,application/pdf', 'idempotency_key' => 'required|uuid']);
        $data['amount_rupiah'] = RupiahInput::parse($data['amount_rupiah'], 'amount_rupiah');
        unset($data['proof']);
        $payment = DB::transaction(function () use ($action, $request, $payable, $data, $documents) {
            $payment = $action->handle($request->user(), $payable, $data);
            $hasProof = Document::query()->where('attachable_type', class_basename($payment))->where('attachable_id', $payment->id)->where('category', 'payable_payment_proof')->exists();
            if (! $hasProof) {
                $documents->store($request->file('proof'), $request->user(), 'payable_payment_proof', $payment);
            }

            return $payment;
        });

        return redirect()->route('payables.show', $payable)->with('status', "Pembayaran {$payment->number} berhasil disimpan.");
    }

    public function voidPayment(Request $request, Payable $payable, PayablePayment $payment, VoidPayablePayment $action): RedirectResponse
    {
        $data = $request->validate(['reason' => ['required', 'string', 'min:10', 'max:500'], 'idempotency_key' => ['required', 'uuid']]);
        $action->handle($request->user(), $payable, $payment, $data['reason'], $data['idempotency_key']);

        return back()->with('status', "Pembayaran {$payment->number} dibatalkan dan saldo kewajiban dihitung ulang.");
    }
}
