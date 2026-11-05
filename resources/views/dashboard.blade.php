<x-layouts::app :title="__('Ringkasan Usaha')">
    <div class="ui-page space-y-6">
        <!-- Header Halaman -->
        <x-ui.page-header 
            eyebrow="Ringkasan Pemilik" 
            :title="'Selamat Datang, ' . auth()->user()->name" 
            description="Pantau kegiatan usaha toko hari ini, omzet penjualan, stok barang, serta tindak lanjuti kewajiban yang perlu diselesaikan."
        >
            <x-slot:actions>
                <div class="inline-flex items-center gap-2 rounded-xl border border-zinc-200/90 bg-white px-3.5 py-2 text-sm font-semibold text-zinc-700 shadow-2xs">
                    <svg class="size-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </x-slot:actions>
        </x-ui.page-header>

        <!-- Aksi Cepat Operasional -->
        <section aria-label="Aksi Cepat Operasional">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @if(auth()->user()->hasPermission('sales.create'))
                    <a href="{{ route('pos.index') }}" wire:navigate class="group flex items-center gap-3.5 rounded-2xl border border-zinc-200/90 bg-white p-4 shadow-2xs transition-all hover:border-accent hover:shadow-md">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-accent text-white shadow-xs group-hover:scale-105 transition-transform">
                            <flux:icon.shopping-cart class="size-5" />
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-zinc-900 group-hover:text-accent">Buka Kasir (POS)</span>
                            <span class="text-xs text-zinc-500">Mulai transaksi kasir baru</span>
                        </div>
                    </a>
                @endif

                @if(auth()->user()->hasPermission('products.create'))
                    <a href="{{ route('catalog.products.create') }}" wire:navigate class="group flex items-center gap-3.5 rounded-2xl border border-zinc-200/90 bg-white p-4 shadow-2xs transition-all hover:border-accent hover:shadow-md">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 shadow-xs group-hover:scale-105 transition-transform">
                            <flux:icon.plus class="size-5" />
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-zinc-900 group-hover:text-accent">Tambah Produk</span>
                            <span class="text-xs text-zinc-500">Daftarkan barang dagangan baru</span>
                        </div>
                    </a>
                @endif

                @if(auth()->user()->hasPermission('purchases.create'))
                    <a href="{{ route('receipts.create') }}" wire:navigate class="group flex items-center gap-3.5 rounded-2xl border border-zinc-200/90 bg-white p-4 shadow-2xs transition-all hover:border-accent hover:shadow-md">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-700 shadow-xs group-hover:scale-105 transition-transform">
                            <flux:icon.truck class="size-5" />
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-zinc-900 group-hover:text-accent">Barang Masuk</span>
                            <span class="text-xs text-zinc-500">Catat penerimaan dari pemasok</span>
                        </div>
                    </a>
                @endif

                @if(auth()->user()->hasPermission('reports.sales'))
                    <a href="{{ route('reports.sales') }}" wire:navigate class="group flex items-center gap-3.5 rounded-2xl border border-zinc-200/90 bg-white p-4 shadow-2xs transition-all hover:border-accent hover:shadow-md">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700 shadow-xs group-hover:scale-105 transition-transform">
                            <flux:icon.chart-bar class="size-5" />
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-zinc-900 group-hover:text-accent">Laporan & Laba</span>
                            <span class="text-xs text-zinc-500">Lihat analisis laba rugi toko</span>
                        </div>
                    </a>
                @endif
            </div>
        </section>

        <!-- Indikator Utama Usaha Hari Ini -->
        <section aria-labelledby="today-heading">
            <div class="mb-3 flex items-center justify-between">
                <h2 id="today-heading" class="text-base font-bold text-zinc-900">Kondisi & Ringkasan Hari Ini</h2>
                <span class="text-xs text-zinc-500">Diperbarui otomatis secara langsung</span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <!-- Penjualan Kasir Hari Ini -->
                <article class="ui-kpi relative overflow-hidden">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Penjualan Kasir</span>
                            <div class="ui-kpi-value">
                                Rp{{ number_format($metrics['sales_total'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="flex size-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <flux:icon.shopping-cart class="size-5" />
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-xs text-zinc-600">
                        <span class="inline-flex items-center rounded-full bg-emerald-100/80 px-2 py-0.5 font-bold text-emerald-800">
                            {{ $metrics['sales_count'] }} Nota
                        </span>
                        <span>transaksi berhasil hari ini</span>
                    </div>
                </article>

                <!-- Penerimaan Barang Hari Ini -->
                <article class="ui-kpi relative overflow-hidden">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Penerimaan Barang</span>
                            <div class="ui-kpi-value">
                                Rp{{ number_format($metrics['receipts_total'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="flex size-11 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                            <flux:icon.truck class="size-5" />
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-xs text-zinc-600">
                        <span>Total nilai belanja stok masuk hari ini</span>
                    </div>
                </article>

                <!-- Hutang Dekat Jatuh Tempo -->
                <article class="ui-kpi relative overflow-hidden">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Hutang Jatuh Tempo</span>
                            <div class="ui-kpi-value text-amber-900">
                                Rp{{ number_format($metrics['payables_due'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="flex size-11 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                            <flux:icon.banknotes class="size-5" />
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-xs text-zinc-600">
                        <span>Harus dibayar dalam 7 hari ke depan</span>
                    </div>
                </article>

                <!-- Produk Stok Menipis -->
                <article class="ui-kpi relative overflow-hidden">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Stok Menipis</span>
                            <div class="ui-kpi-value {{ $metrics['low_stock'] > 0 ? 'text-rose-600' : 'text-zinc-950' }}">
                                {{ $metrics['low_stock'] }} Produk
                            </div>
                        </div>
                        <div class="flex size-11 items-center justify-center rounded-xl {{ $metrics['low_stock'] > 0 ? 'bg-rose-50 text-rose-700' : 'bg-zinc-100 text-zinc-600' }}">
                            <flux:icon.archive-box class="size-5" />
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-xs text-zinc-600">
                        <a href="{{ route('inventory.index', ['low_stock' => 1]) }}" wire:navigate class="font-bold text-accent hover:underline">
                            Periksa & tambah stok &rarr;
                        </a>
                    </div>
                </article>
            </div>
        </section>

        <!-- Grafik Tren Penjualan 7 Hari Terakhir -->
        <section class="ui-panel">
            <div class="ui-panel-header">
                <div>
                    <h2 class="font-bold text-zinc-900 text-base">Tren Penjualan 7 Hari Terakhir</h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Grafik uang masuk dari transaksi kasir yang selesai diposting.</p>
                </div>
                <a href="{{ route('reports.sales') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-accent hover:underline">
                    <span>Buka Laporan Lengkap</span>
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="ui-panel-body">
                @php($max = max(1, $trend->max('total')))
                <div class="flex h-64 items-end gap-2.5 sm:gap-4 border-b border-zinc-200 px-2 pb-3">
                    @foreach ($trend as $day)
                        @php($heightPct = max(3, round(($day['total'] / $max) * 85)))
                        <div class="group relative flex h-full flex-1 flex-col items-center justify-end">
                            <!-- Tooltip On Hover -->
                            <div class="pointer-events-none absolute -top-12 z-20 hidden whitespace-nowrap rounded-xl bg-zinc-900 px-3 py-1.5 text-xs font-semibold text-white shadow-xl group-hover:block">
                                <div class="text-[11px] text-zinc-300">{{ $day['label'] }}</div>
                                <div class="text-emerald-400 font-bold">Rp{{ number_format($day['total'], 0, ',', '.') }}</div>
                            </div>

                            <!-- Nilai Singkat di Atas Batang -->
                            <span class="mb-2 text-xs font-bold text-zinc-500 group-hover:text-accent">
                                {{ $day['total'] ? (round($day['total'] / 1000) >= 1000 ? round($day['total'] / 1000000, 1).' jt' : round($day['total'] / 1000).' rb') : '0' }}
                            </span>

                            <!-- Batang Grafik -->
                            <div 
                                class="w-full max-w-14 rounded-t-xl transition-all duration-200 group-hover:brightness-110 {{ $day['total'] > 0 ? 'bg-accent shadow-xs' : 'bg-zinc-100' }}" 
                                style="height: {{ $heightPct }}%"
                            ></div>

                            <!-- Label Hari -->
                            <span class="mt-2.5 text-xs font-medium text-zinc-600 group-hover:font-bold group-hover:text-zinc-950">
                                {{ $day['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Aktivitas Terbaru Toko -->
        <section class="ui-panel">
            <div class="ui-panel-header">
                <div>
                    <h2 class="font-bold text-zinc-900 text-base">Aktivitas & Log Terbaru Toko</h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Catatan transaksi kasir, perubahan stok barang, dan aktivitas pengguna yang aman dan transparan.</p>
                </div>
                @if(auth()->user()->hasPermission('audit.view'))
                    <a href="{{ route('admin.audit') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-accent hover:underline">
                        <span>Lihat Semua Log Audit</span>
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif
            </div>

            @if ($activities->isEmpty())
                <div class="p-8 text-center text-sm text-zinc-500">
                    Belum ada riwayat aktivitas terbaru yang tercatat.
                </div>
            @else
                <div class="ui-table-wrap">
                    <table class="ui-table">
                        <thead>
                            <tr>
                                <th>Waktu Kejadian</th>
                                <th>Nama Aktivitas</th>
                                <th>Pengguna / Petugas</th>
                                <th>ID Transaksi / Permintaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $activity)
                                <tr>
                                    <td class="font-medium text-zinc-600 whitespace-nowrap">
                                        {{ $activity->occurred_at->format('H.i') }} WIB
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="size-2 rounded-full bg-{{ $activity->event_badge_color }}-500 shrink-0"></span>
                                            <span class="font-bold text-zinc-900">{{ $activity->event_label }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-zinc-800">
                                            {{ $activity->actor?->name ?? 'Sistem Otomatis' }}
                                        </span>
                                    </td>
                                    <td class="text-xs font-mono text-zinc-500">
                                        {{ str($activity->request_id)->limit(18) ?: '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</x-layouts::app>
