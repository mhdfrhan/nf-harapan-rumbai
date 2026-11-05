<x-layouts::app :title="'Penerimaan ' . $receipt->number">
    <div class="ui-page max-w-4xl space-y-6">
        <x-ui.page-header 
            eyebrow="Gudang & Pemasok" 
            :title="$receipt->number" 
            :description="'Diterima dari pemasok ' . $receipt->supplier->name . ' pada tanggal ' . $receipt->received_on->translatedFormat('d F Y')"
        >
            <x-slot:actions>
                @if($receipt->documents->first())
                    <a class="ui-btn font-semibold text-xs flex items-center gap-2" href="{{ route('documents.download', $receipt->documents->first()) }}">
                        <flux:icon.arrow-down-tray class="size-4 text-zinc-600" />
                        <span>Unduh Bukti Bon</span>
                    </a>
                @endif
                <a href="{{ route('receipts.index') }}" wire:navigate class="ui-btn text-xs font-semibold">
                    <span>Kembali ke Daftar</span>
                </a>
            </x-slot:actions>
        </x-ui.page-header>

        @if(session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <!-- Panel Ringkasan Status -->
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-xs">
                <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Status Dokumen</span>
                <div class="mt-2">
                    <x-ui.status :value="match($receipt->status->value){'posted'=>'Diposting','voided'=>'Dibatalkan',default=>str($receipt->status->value)->replace('_',' ')->title()}"/>
                </div>
                <p class="mt-2 text-xs text-zinc-500">ID: {{ $receipt->id }}</p>
            </div>

            <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-xs">
                <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Status Pembayaran</span>
                <div class="mt-2">
                    <x-ui.status :value="match(strtolower($receipt->payment_state)){'paid'=>'Lunas','partial'=>'Dibayar Sebagian','unpaid'=>'Belum Lunas',default=>str($receipt->payment_state)->replace('_',' ')->title()}"/>
                </div>
                <p class="mt-2 text-xs text-zinc-500">Pemasok: {{ $receipt->supplier->name }}</p>
            </div>

            <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-xs">
                <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Nilai Tagihan</span>
                <p class="mt-2 text-2xl font-extrabold text-accent">Rp{{ number_format($receipt->total_rupiah, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-zinc-500">{{ $receipt->items->count() }} macam produk</p>
            </div>
        </div>

        <!-- Tabel Rincian Barang Masuk -->
        <section class="ui-panel overflow-hidden">
            <div class="ui-panel-header">
                <h3 class="text-base font-bold text-zinc-950">Daftar Barang yang Diterima</h3>
                <span class="text-xs text-zinc-500">Saldo stok langsung bertambah</span>
            </div>

            <div class="ui-table-wrap border-0 shadow-none">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nama Produk Barang</th>
                            <th>Kemasan Satuan</th>
                            <th>Jumlah Masuk</th>
                            <th>Harga Modal Beli</th>
                            <th class="text-right">Subtotal Belanja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receipt->items as $item)
                            <tr>
                                <td>
                                    <strong class="text-sm font-bold text-zinc-950 block">{{ $item->product_name }}</strong>
                                </td>
                                <td>{{ $item->unit_label }}</td>
                                <td>
                                    <strong class="text-sm text-zinc-900">
                                        {{ rtrim(rtrim(number_format((float)$item->quantity, 4, ',', '.'), '0'), ',') }}
                                    </strong>
                                </td>
                                <td>Rp{{ number_format($item->unit_cost_rupiah, 0, ',', '.') }}</td>
                                <td class="text-right font-bold text-zinc-950">Rp{{ number_format($item->subtotal_rupiah, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-zinc-100 bg-cream-50/60 p-5 flex items-center justify-between">
                <span class="text-xs text-zinc-500">Semua item telah divalidasi dan dicatat ke kartu persediaan gudang toko.</span>
                <div class="text-right">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 block">Total Tagihan Belanja</span>
                    <strong class="text-2xl font-extrabold text-accent">Rp{{ number_format($receipt->total_rupiah, 0, ',', '.') }}</strong>
                </div>
            </div>
        </section>

        <!-- Aksi Terkait: Lihat Hutang atau Void Penerimaan -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
            @if($receipt->payable)
                <a class="ui-btn ui-btn-primary text-xs font-bold" href="{{ route('payables.show', $receipt->payable) }}" wire:navigate>
                    <flux:icon.banknotes class="size-4" />
                    <span>Lihat Rekap Hutang ({{ $receipt->payable->number }})</span>
                </a>
            @else
                <div></div>
            @endif

            @if(auth()->user()->hasPermission('purchases.void') && $receipt->status === \App\Domain\Procurement\Enums\GoodsReceiptStatus::Posted)
                <form method="POST" action="{{ route('receipts.void', $receipt) }}" class="flex flex-wrap items-center gap-2" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan penerimaan barang ini? Seluruh dampak stok akan otomatis ditarik kembali.')">
                    @csrf
                    <input type="hidden" name="idempotency_key" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                    <input name="reason" class="ui-input text-xs w-64" minlength="10" maxlength="1000" placeholder="Alasan pembatalan..." required>
                    <button class="ui-btn ui-btn-danger text-xs font-bold" type="submit">
                        Batalkan Dokumen
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-layouts::app>
