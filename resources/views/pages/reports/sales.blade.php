<x-layouts::app :title="__('Laporan Penjualan & Laba')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Analisis Usaha" 
            title="Laporan Penjualan & Keuntungan Toko" 
            description="Pantau uang masuk kasir, perkiraan modal belanja barang, dan keuntungan bersih toko secara langsung tanpa rumus akuntansi yang rumit."
        />

        @include('pages.reports._layout')

        <!-- 1. Panel Filter Terpadu di Bagian Atas -->
        <section class="ui-panel p-5">
            <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between border-b border-zinc-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="flex size-8 items-center justify-center rounded-xl bg-accent text-white">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-zinc-950">Filter Periode & Kriteria Laporan</h2>
                        <span class="text-xs text-zinc-500">Pilih rentang tanggal untuk memperbarui statistik omzet, laba bersih, dan grafik</span>
                    </div>
                </div>
            </div>

            <form method="GET" class="space-y-4">
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                    <div>
                        <label class="ui-label text-xs mb-1 block" for="from">Dari Tanggal</label>
                        <input id="from" name="from" type="date" class="ui-input text-xs font-medium" value="{{ request('from', today()->startOfMonth()->toDateString()) }}">
                    </div>

                    <div>
                        <label class="ui-label text-xs mb-1 block" for="to">Sampai Tanggal</label>
                        <input id="to" name="to" type="date" class="ui-input text-xs font-medium" value="{{ request('to', today()->toDateString()) }}">
                    </div>

                    <div>
                        <label class="ui-label text-xs mb-1 block" for="cashier_id">Petugas Kasir</label>
                        <select id="cashier_id" name="cashier_id" class="ui-select text-xs">
                            <option value="">Semua Kasir</option>
                            @foreach($cashiers as $cashier)
                                <option value="{{ $cashier->id }}" @selected((string)request('cashier_id') === (string)$cashier->id)>
                                    {{ $cashier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="ui-label text-xs mb-1 block" for="product_id">Produk Barang</label>
                        <select id="product_id" name="product_id" class="ui-select text-xs">
                            <option value="">Semua Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" @selected((string)request('product_id') === (string)$product->id)>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="ui-label text-xs mb-1 block" for="price_type">Tipe Harga</label>
                        <select id="price_type" name="price_type" class="ui-select text-xs">
                            <option value="">Semua Tipe Harga</option>
                            <option value="retail" @selected(request('price_type') === 'retail')>Harga Eceran</option>
                            <option value="wholesale" @selected(request('price_type') === 'wholesale')>Harga Grosir</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-2 pt-2 border-t border-zinc-100">
                    <button class="ui-btn ui-btn-primary text-xs font-bold shadow-xs" type="submit">
                        <svg class="size-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Terapkan Filter & Tampilkan</span>
                    </button>

                    @if(auth()->user()->hasPermission('reports.export'))
                        <button class="ui-btn text-xs font-semibold" name="export" value="1" type="submit">
                            <svg class="size-4 mr-1 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Unduh Laporan (.CSV)</span>
                        </button>
                    @endif
                </div>
            </form>
        </section>

        @if(isset($metrics))
            <!-- 2. Kartu Statistik Utama -->
            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Penjualan / Omzet -->
                <article class="ui-kpi relative overflow-hidden">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Uang Penjualan (Omzet)</span>
                        <div class="flex size-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                    <p class="ui-kpi-value">
                        Rp{{ number_format($metrics['total_sales'], 0, ',', '.') }}
                    </p>
                    <p class="mt-1.5 text-xs text-zinc-500">
                        Seluruh uang belanja pembeli yang masuk kasir
                    </p>
                </article>

                <!-- Modal Barang Terjual (HPP) -->
                <article class="ui-kpi relative overflow-hidden">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Modal Belanja Barang</span>
                        <div class="flex size-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <p class="ui-kpi-value text-amber-900">
                        Rp{{ number_format($metrics['total_cost'], 0, ',', '.') }}
                    </p>
                    <p class="mt-1.5 text-xs text-zinc-500">
                        Modal awal kulakan barang yang laku terjual
                    </p>
                </article>

                <!-- Keuntungan Bersih Toko -->
                <article class="ui-kpi relative overflow-hidden border-emerald-200 bg-emerald-50/40">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Keuntungan Toko (Laba Bersih)</span>
                        <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-bold text-emerald-800 border border-emerald-200">
                            +{{ $metrics['profit_margin'] }}%
                        </span>
                    </div>
                    <p class="ui-kpi-value text-emerald-800">
                        Rp{{ number_format($metrics['gross_profit'], 0, ',', '.') }}
                    </p>
                    <p class="mt-1.5 text-xs text-emerald-800 font-medium">
                        Uang untung bersih setelah dikurangi modal
                    </p>
                </article>

                <!-- Banyaknya Transaksi & Rata-rata -->
                <article class="ui-kpi relative overflow-hidden">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Jumlah Transaksi</span>
                        <div class="flex size-10 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <p class="ui-kpi-value">
                        {{ number_format($metrics['sales_count']) }} Nota
                    </p>
                    <p class="mt-1.5 text-xs text-zinc-500">
                        Rata-rata belanja Rp{{ number_format($metrics['avg_sale'], 0, ',', '.') }} per nota
                    </p>
                </article>
            </section>

            <!-- 3. Rangkuman Sederhana Laba Rugi Toko -->
            <section class="ui-panel p-5 sm:p-6">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between border-b border-zinc-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-zinc-950">Hitungan Sederhana Keuntungan Toko (Laba - Rugi)</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Ringkasan hasil usaha toko pada rentang tanggal dan kriteria filter yang dipilih di atas.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Data Terverifikasi Kasir</span>
                    </span>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl bg-cream-50/70 p-4 border border-zinc-200">
                        <span class="text-xs font-semibold text-zinc-500 uppercase">(+) Uang Masuk Penjualan</span>
                        <div class="mt-1.5 text-lg sm:text-xl font-extrabold text-zinc-950">Rp{{ number_format($metrics['total_sales'], 0, ',', '.') }}</div>
                        <p class="mt-1 text-xs text-zinc-500">Total uang belanja dari pembeli</p>
                    </div>

                    <div class="rounded-2xl bg-cream-50/70 p-4 border border-zinc-200">
                        <span class="text-xs font-semibold text-zinc-500 uppercase">(-) Modal Kulakan Barang</span>
                        <div class="mt-1.5 text-lg sm:text-xl font-extrabold text-rose-700">Rp{{ number_format($metrics['total_cost'], 0, ',', '.') }}</div>
                        <p class="mt-1 text-xs text-zinc-500">Harga beli barang dari supplier</p>
                    </div>

                    <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200">
                        <span class="text-xs font-bold text-emerald-800 uppercase">(=) Sisa Uang Untung Toko</span>
                        <div class="mt-1.5 text-lg sm:text-xl font-extrabold text-emerald-800">Rp{{ number_format($metrics['gross_profit'], 0, ',', '.') }}</div>
                        <p class="mt-1 text-xs text-emerald-800 font-medium">Toko untung {{ $metrics['profit_margin'] }}% dari omzet</p>
                    </div>
                </div>

                <div class="mt-4 rounded-xl bg-sky-50/70 p-3.5 text-xs text-sky-950 border border-sky-100 leading-relaxed">
                    <strong>Panduan Penjelasan:</strong> Keuntungan bersih toko dihitung secara otomatis dengan membandingkan harga jual di kasir dengan modal harga beli barang dari faktur pemasok. Anda tidak perlu repot menghitung pembukuan akuntansi manual yang memusingkan.
                </div>
            </section>

            <!-- 4. Grafik Visual Tren Penjualan Harian -->
            <section class="ui-panel p-5 sm:p-6">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between border-b border-zinc-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-zinc-950">Grafik Tren Penjualan Harian</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Arahkan kursor atau sentuh batang grafik untuk melihat omzet penjualan per hari.</p>
                    </div>
                    <div class="text-xs font-medium text-zinc-500">
                        Penjualan Tertinggi: <strong class="text-accent font-bold">Rp{{ number_format($metrics['max_daily_total'], 0, ',', '.') }}</strong>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex h-56 items-end gap-2 sm:gap-3 border-b border-zinc-200 px-2 pb-2">
                        @foreach ($metrics['chart_points'] as $pt)
                            @php($heightPct = max(3, round(($pt['total'] / $metrics['max_daily_total']) * 85)))
                            <div 
                                class="group relative flex h-full flex-1 flex-col items-center justify-end"
                                title="{{ $pt['date'] }}: Rp{{ number_format($pt['total'], 0, ',', '.') }} ({{ $pt['count'] }} nota)"
                            >
                                <!-- Tooltip on Hover -->
                                <div class="pointer-events-none absolute -top-12 z-20 hidden whitespace-nowrap rounded-xl bg-zinc-950 px-3 py-1.5 text-xs font-semibold text-white shadow-xl group-hover:block">
                                    <div class="text-zinc-300">{{ $pt['label'] }} ({{ $pt['dayName'] }})</div>
                                    <div class="text-emerald-400 font-bold">Rp{{ number_format($pt['total'], 0, ',', '.') }}</div>
                                </div>

                                <!-- Label Singkat di Atas Batang -->
                                <span class="mb-1.5 text-[11px] font-bold text-zinc-500 group-hover:text-accent">
                                    {{ $pt['total'] > 0 ? (round($pt['total'] / 1000) >= 1000 ? round($pt['total'] / 1000000, 1).'jt' : round($pt['total'] / 1000).'rb') : '-' }}
                                </span>

                                <!-- Batang Grafik Bar -->
                                <div 
                                    class="w-full max-w-10 rounded-t-xl transition-all duration-200 group-hover:brightness-110 {{ $pt['total'] > 0 ? 'bg-accent shadow-xs' : 'bg-zinc-100' }}"
                                    style="height: {{ $heightPct }}%;"
                                ></div>

                                <!-- Label Tanggal Bawah -->
                                <span class="mt-2 text-xs text-zinc-600 group-hover:font-bold group-hover:text-zinc-950 truncate max-w-full text-center">
                                    {{ $pt['label'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- 5. Tabel Rincian Transaksi Penjualan -->
        <section class="ui-panel overflow-hidden">
            <div class="ui-panel-header">
                <div>
                    <h3 class="font-bold text-zinc-950 text-base">Daftar Rincian Nota Penjualan Kasir</h3>
                    <p class="text-xs text-zinc-500 mt-0.5">Daftar transaksi yang selesai dicatat dan sah pada periode ini.</p>
                </div>
            </div>

            <div class="ui-table-wrap border-0 shadow-none">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nomor Struk</th>
                            <th>Tanggal & Waktu</th>
                            <th>Petugas Kasir</th>
                            <th>Total Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $sale)
                            <tr>
                                <td>
                                    <a class="font-bold text-accent hover:underline flex items-center gap-1" href="{{ route('sales.show', $sale) }}" wire:navigate>
                                        <span>{{ $sale->number }}</span>
                                    </a>
                                </td>
                                <td class="font-medium text-zinc-600">{{ $sale->sold_at->translatedFormat('d M Y, H.i') }} WIB</td>
                                <td class="font-semibold text-zinc-900">{{ $sale->cashier?->name ?? 'Kasir Toko' }}</td>
                                <td>
                                    <strong class="text-sm font-extrabold text-zinc-950">
                                        Rp{{ number_format($sale->total_rupiah, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-zinc-500 text-sm">
                                    Belum ada transaksi penjualan pada rentang tanggal atau filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $rows->links() }}
        </div>
    </div>
</x-layouts::app>
