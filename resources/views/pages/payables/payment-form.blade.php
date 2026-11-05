<x-layouts::app :title="__('Catat Pembayaran Hutang')">
    <div class="ui-page max-w-3xl space-y-6">
        <x-ui.page-header 
            eyebrow="Keuangan & Pemasok" 
            :title="'Catat Pembayaran: ' . $payable->number" 
            :description="'Pembayaran tagihan kepada pihak pemasok ' . $payable->supplier->name"
        />

        @if($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-medium text-rose-800">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" enctype="multipart/form-data" action="{{ route('payables.payments.store', $payable) }}" class="ui-panel overflow-hidden">
            @csrf
            <input type="hidden" name="idempotency_key" value="{{ $idempotencyKey }}">

            <div class="ui-panel-body space-y-5">
                <!-- KPI Sisa Hutang yang Harus Dibayar -->
                <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-5">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-800">Sisa Tagihan Hutang Saat Ini</span>
                    <p class="mt-2 text-3xl font-extrabold text-amber-900">
                        Rp{{ number_format($payable->balance_rupiah, 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-amber-700">Total belanja awal: Rp{{ number_format($payable->principal_rupiah, 0, ',', '.') }}</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="ui-label" for="paid_on">Tanggal Pembayaran</label>
                        <input id="paid_on" name="paid_on" type="date" value="{{ today()->toDateString() }}" class="ui-input font-medium" required>
                    </div>

                    <div>
                        <label class="ui-label" for="method">Cara Pembayaran</label>
                        <select id="method" name="method" class="ui-select">
                            <option value="cash">Tunai (Kas Toko)</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="ui-label" for="amount_rupiah">Jumlah Nominal Uang Dibayar (Rp)</label>
                    <input 
                        id="amount_rupiah" 
                        name="amount_rupiah" 
                        class="ui-input text-lg font-bold text-accent" 
                        inputmode="numeric" 
                        placeholder="Contoh: {{ $payable->balance_rupiah }}" 
                        value="{{ $payable->balance_rupiah }}"
                        required
                    >
                    <p class="mt-1.5 text-xs text-zinc-500">Anda dapat membayar lunas penuh atau mencicil sebagian.</p>
                </div>

                <div>
                    <label class="ui-label" for="reference">Nomor Referensi / Bukti Transfer (Opsional)</label>
                    <input id="reference" name="reference" class="ui-input font-mono text-xs" placeholder="Contoh: TRF-20261102-0988">
                </div>

                <div>
                    <label class="ui-label" for="proof">Unggah Foto Bukti Transfer / Kwitansi Pelunasan (Wajib)</label>
                    <input id="proof" name="proof" type="file" accept="image/jpeg,image/png,application/pdf" class="ui-input pt-2 text-xs" required>
                    <p class="mt-1.5 text-xs text-zinc-500">Foto struk ATM, bukti m-banking, atau kwitansi resmi yang ditandatangani.</p>
                </div>

                <div>
                    <label class="ui-label" for="notes">Catatan Tambahan (Opsional)</label>
                    <textarea id="notes" name="notes" class="ui-textarea" placeholder="Catatan pembayaran..."></textarea>
                </div>
            </div>

            <div class="border-t border-zinc-100 bg-cream-50/60 p-5 flex items-center justify-end gap-3">
                <a class="ui-btn font-semibold text-xs" href="{{ route('payables.show', $payable) }}" wire:navigate>
                    Batal
                </a>
                <button class="ui-btn ui-btn-primary font-bold text-xs shadow-sm" type="submit">
                    Simpan Pembayaran Hutang
                </button>
            </div>
        </form>
    </div>
</x-layouts::app>
