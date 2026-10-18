<x-layouts::app :title="__('Pemasok')">
    <div class="ui-page">
        <x-ui.page-header eyebrow="Pemasok dan kewajiban" title="Pemasok" description="Simpan kontak pemasok dan pantau sisa kewajiban per pemasok." />

        @if (auth()->user()->hasPermission('suppliers.manage'))
            <form method="POST" action="{{ route('suppliers.store') }}" class="ui-panel p-5">
                @csrf
                <h2 class="font-semibold text-zinc-900">Tambah pemasok</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div><label class="ui-label" for="name">Nama pemasok</label><input id="name" name="name" class="ui-input" value="{{ old('name') }}" required maxlength="180"></div>
                    <div><label class="ui-label" for="phone">Telepon</label><input id="phone" name="phone" class="ui-input" value="{{ old('phone') }}" maxlength="40"></div>
                    <div><label class="ui-label" for="address">Alamat</label><textarea id="address" name="address" class="ui-textarea" maxlength="1000">{{ old('address') }}</textarea></div>
                    <div><label class="ui-label" for="notes">Catatan</label><textarea id="notes" name="notes" class="ui-textarea" maxlength="1000">{{ old('notes') }}</textarea></div>
                </div>
                @if ($errors->any())<div class="mt-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif
                <button class="ui-btn ui-btn-primary mt-4" type="submit"><flux:icon.plus class="size-4" /> Tambah pemasok</button>
            </form>
        @endif

        <form method="GET" class="ui-toolbar">
            <div class="relative min-w-0 flex-1"><flux:icon.magnifying-glass class="absolute left-3 top-3.5 size-4 text-zinc-500" /><input name="search" class="ui-input pl-10" value="{{ request('search') }}" placeholder="Cari nama atau nomor telepon"></div>
            <select name="status" class="ui-select sm:w-40"><option value="">Semua status</option><option value="active" @selected(request('status') === 'active')>Aktif</option><option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option></select>
            <button class="ui-btn" type="submit">Terapkan</button>
        </form>

        @if ($suppliers->isEmpty())
            <x-ui.state-frame emptyTitle="Belum ada pemasok" emptyDescription="Tambahkan pemasok sebelum mencatat penerimaan barang." />
        @else
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($suppliers as $supplier)
                    <a href="{{ route('suppliers.show', $supplier) }}" class="ui-panel p-5 hover:border-zinc-500">
                        <div class="flex items-start justify-between"><div class="flex size-11 items-center justify-center rounded-[10px] bg-[#e2ece6] text-sm font-bold text-accent">{{ str($supplier->name)->explode(' ')->map(fn ($word) => str($word)->substr(0, 1))->take(2)->join('') }}</div><x-ui.status :value="$supplier->is_active ? 'Aktif' : 'Nonaktif'" /></div>
                        <h2 class="mt-5 font-semibold text-zinc-900">{{ $supplier->name }}</h2>
                        <p class="mt-2 text-sm text-zinc-600">{{ $supplier->phone ?: 'Telepon belum diisi' }}</p>
                        <p class="mt-1 line-clamp-2 text-sm text-zinc-600">{{ $supplier->address ?: 'Alamat belum diisi' }}</p>
                        <div class="mt-5 border-t border-zinc-300 pt-4"><p class="text-xs text-zinc-500">Sisa kewajiban</p><p class="mt-1 font-semibold text-zinc-900">Rp{{ number_format((int) ($supplier->open_balance ?? 0), 0, ',', '.') }}</p></div>
                    </a>
                @endforeach
            </div>
            <div>{{ $suppliers->links() }}</div>
        @endif
    </div>
</x-layouts::app>
