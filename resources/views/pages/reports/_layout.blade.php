<nav class="flex gap-2 overflow-x-auto border-b border-zinc-200 pb-2" aria-label="Jenis Laporan">
    @foreach ([
        ['reports.sales', 'Penjualan & Keuntungan Toko', 'reports.sales'], 
        ['reports.inventory', 'Laporan Stok Barang (Persediaan)', 'reports.stock'], 
        ['reports.purchases', 'Laporan Pembelian (Barang Masuk)', 'reports.stock'], 
        ['reports.payables', 'Laporan Hutang Pemasok (Kewajiban)', 'reports.payables']
    ] as [$routeName, $label, $permission])
        @if (auth()->user()->hasPermission($permission))
            <a 
                href="{{ route($routeName) }}" 
                wire:navigate
                class="min-h-10 shrink-0 rounded-xl px-4 py-2 text-xs sm:text-sm font-bold transition-all {{ request()->routeIs($routeName) ? 'bg-accent text-white shadow-xs' : 'text-zinc-600 hover:text-zinc-950 hover:bg-cream-200/60' }}"
            >
                {{ $label }}
            </a>
        @endif
    @endforeach
</nav>

@if (! request()->routeIs('reports.sales'))
    <section class="ui-panel p-4 sm:p-5 mt-4">
        <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            @if (! request()->routeIs('reports.inventory'))
                <div>
                    <label class="ui-label text-xs mb-1 block" for="from">Dari Tanggal</label>
                    <input id="from" name="from" type="date" class="ui-input text-xs sm:w-48 font-medium" value="{{ request('from', today()->startOfMonth()->toDateString()) }}">
                </div>
                <div>
                    <label class="ui-label text-xs mb-1 block" for="to">Sampai Tanggal</label>
                    <input id="to" name="to" type="date" class="ui-input text-xs sm:w-48 font-medium" value="{{ request('to', today()->toDateString()) }}">
                </div>
            @endif

            <div class="flex items-center gap-2">
                <button class="ui-btn ui-btn-primary text-xs font-bold" type="submit">
                    Tampilkan Laporan
                </button>

                @if (auth()->user()->hasPermission('reports.export'))
                    <button name="export" value="1" class="ui-btn text-xs font-semibold" type="submit">
                        <flux:icon.arrow-down-tray class="size-3.5 text-zinc-500" />
                        <span>Unduh CSV</span>
                    </button>
                @endif
            </div>
        </form>
    </section>
@endif
