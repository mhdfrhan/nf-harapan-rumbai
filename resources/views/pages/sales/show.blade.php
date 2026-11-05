<x-layouts::app :title="'Struk ' . $sale->number">
    <div class="ui-page max-w-4xl space-y-6">
        <!-- Cetak Header Toko Saat Print -->
        <div class="hidden print:block text-center pb-4 border-b border-zinc-300">
            <h1 class="text-xl font-bold text-zinc-950">{{ $storeProfile['name'] }}</h1>
            <p class="text-xs text-zinc-600">{{ $storeProfile['address'] }}</p>
            <p class="text-xs text-zinc-600">Telp: {{ $storeProfile['phone'] }}</p>
            <div class="mt-2 text-xs font-mono">No. Nota: {{ $sale->number }} &bull; {{ $sale->sold_at->format('d/m/Y H:i') }} WIB &bull; Kasir: {{ $sale->cashier->name }}</div>
        </div>

        <x-ui.page-header 
            eyebrow="Rincian Transaksi" 
            :title="$sale->number" 
            :description="'Dicatat pada ' . $sale->sold_at->translatedFormat('d F Y, H.i') . ' WIB oleh kasir ' . $sale->cashier->name"
        >
            <x-slot:actions>
                <button onclick="window.print()" class="ui-btn font-semibold print:hidden flex items-center gap-2">
                    <svg class="size-4 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Struk</span>
                </button>
                <a href="{{ route('sales.index') }}" wire:navigate class="ui-btn print:hidden">
                    <span>Kembali ke Riwayat</span>
                </a>
            </x-slot:actions>
        </x-ui.page-header>

        @if(session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <!-- Panel Rincian Pembayaran & Status -->
        <div class="grid gap-4 sm:grid-cols-3 print:grid-cols-3">
            <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-xs">
                <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Status Transaksi</span>
                <div class="mt-2">
                    <x-ui.status :value="match($sale->status->value){'posted'=>'Diposting','voided'=>'Dibatalkan',default=>str($sale->status->value)->title()}"/>
                </div>
                <p class="mt-2 text-xs text-zinc-500">ID: {{ $sale->id }}</p>
            </div>

            <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-xs">
                <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Metode Pembayaran</span>
                <p class="mt-2 text-lg font-bold text-zinc-950 uppercase">{{ $sale->payment?->method?->value ?? 'Tunai' }}</p>
                <p class="mt-1 text-xs text-zinc-500">Diterima: Rp{{ number_format($sale->payment?->received_rupiah ?? $sale->total_rupiah, 0, ',', '.') }}</p>
            </div>

            <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-xs">
                <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Uang Kembalian</span>
                <p class="mt-2 text-xl font-extrabold text-emerald-700">Rp{{ number_format($sale->payment?->change_rupiah ?? 0, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-zinc-500">Diberikan ke pembeli</p>
            </div>
        </div>

        <!-- Tabel Rincian Produk Barang Terjual -->
        <section class="ui-panel overflow-hidden">
            <div class="ui-panel-header">
                <h3 class="text-base font-bold text-zinc-950">Daftar Barang yang Dibeli</h3>
                <span class="text-xs text-zinc-500">{{ $sale->items->count() }} macam produk</span>
            </div>

            <div class="ui-table-wrap border-0 shadow-none">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Tipe Harga</th>
                            <th>Harga Satuan</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                            <tr>
                                <td>
                                    <strong class="text-sm font-bold text-zinc-950 block">{{ $item->product_name }}</strong>
                                    <span class="text-xs font-mono text-zinc-500">{{ $item->sku }}</span>
                                </td>
                                <td>{{ $item->unit_label }}</td>
                                <td>
                                    <strong class="text-sm text-zinc-900">
                                        {{ rtrim(rtrim(number_format((float)$item->quantity, 4, ',', '.'), '0'), ',') }}
                                    </strong>
                                </td>
                                <td>
                                    <span class="rounded-md bg-zinc-100 px-2 py-0.5 text-xs font-semibold text-zinc-700">
                                        {{ $item->price_type === \App\Domain\Catalog\Enums\PriceType::Wholesale ? 'Grosir' : 'Eceran' }}
                                    </span>
                                </td>
                                <td>Rp{{ number_format($item->unit_price_rupiah, 0, ',', '.') }}</td>
                                <td class="text-right font-bold text-zinc-950">Rp{{ number_format($item->subtotal_rupiah, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-zinc-100 bg-cream-50/60 p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-xs text-zinc-500">
                    Semua harga sudah termasuk perhitungan konversi satuan dasar dan diskon grosir jika berlaku.
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Total Keseluruhan</span>
                    <div class="text-2xl sm:text-3xl font-extrabold text-accent">
                        Rp{{ number_format($sale->total_rupiah, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </section>

        <!-- Formulir Pembatalan Transaksi (Void) -->
        @if(auth()->user()->hasPermission('sales.void') && $sale->status === \App\Domain\Sales\Enums\SaleStatus::Posted)
            <section class="rounded-2xl border border-rose-200 bg-white p-5 sm:p-6 shadow-xs print:hidden">
                <div class="flex items-center gap-2.5 text-rose-800 mb-2">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="text-base font-bold">Batalkan Transaksi Ini (Void)</h3>
                </div>
                <p class="text-xs text-zinc-600 mb-4">
                    Jika pembeli salah transaksi atau membatalkan seluruh pembelian, Anda dapat membatalkan nota ini. Saldo stok gudang akan otomatis dikembalikan.
                </p>

                <form method="POST" action="{{ route('sales.void', $sale) }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="idempotency_key" value="{{ (string)\Illuminate\Support\Str::uuid() }}">
                    
                    <div>
                        <label class="ui-label text-xs">Alasan Pembatalan (Wajib Diisi)</label>
                        <textarea 
                            name="reason" 
                            class="ui-textarea text-xs" 
                            placeholder="Tuliskan alasan jelas mengapa transaksi ini dibatalkan (minimal 10 karakter)..." 
                            minlength="10" 
                            required
                        ></textarea>
                    </div>

                    <button 
                        type="submit" 
                        onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini dan mengembalikan stok barang?')"
                        class="ui-btn ui-btn-danger text-xs font-bold"
                    >
                        Batalkan Transaksi & Kembalikan Stok Barang
                    </button>
                </form>
            </section>
        @endif

        <p class="hidden print:block text-center text-xs text-zinc-500 pt-4">
            {{ $storeProfile['receipt_footer'] }}
        </p>
    </div>
</x-layouts::app>
