<nav class="flex gap-1 overflow-x-auto border-b border-zinc-300" aria-label="Jenis laporan">
    @foreach ([['reports.sales', 'Penjualan', 'reports.sales'], ['reports.inventory', 'Persediaan', 'reports.stock'], ['reports.purchases', 'Pembelian', 'reports.stock'], ['reports.payables', 'Kewajiban', 'reports.payables']] as [$routeName, $label, $permission])
        @if (auth()->user()->hasPermission($permission))<a href="{{ route($routeName) }}" class="min-h-11 shrink-0 border-b-2 px-4 py-3 text-sm font-semibold {{ request()->routeIs($routeName) ? 'border-accent text-accent' : 'border-transparent text-zinc-600 hover:text-zinc-900' }}">{{ $label }}</a>@endif
    @endforeach
</nav>
<form method="GET" class="ui-toolbar">
    @if (! request()->routeIs('reports.inventory'))<div><label class="ui-label" for="from">Dari tanggal</label><input id="from" name="from" type="date" class="ui-input sm:w-44" value="{{ request('from', today()->startOfMonth()->toDateString()) }}"></div><div><label class="ui-label" for="to">Sampai tanggal</label><input id="to" name="to" type="date" class="ui-input sm:w-44" value="{{ request('to', today()->toDateString()) }}"></div>@endif
    <div class="sm:self-end"><button class="ui-btn ui-btn-primary" type="submit">Tampilkan</button></div>
    @if (auth()->user()->hasPermission('reports.export') && !request()->routeIs('reports.sales'))<div class="sm:self-end"><button name="export" value="1" class="ui-btn" type="submit"><flux:icon.arrow-down-tray class="size-4" /> Ekspor CSV</button></div>@endif
</form>
