<x-layouts::app :title="__('Daftar Pemasok')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Pemasok & Rekanan" 
            title="Daftar Pemasok Toko" 
            description="Kelola data distributor, nomor kontak agen, alamat pengiriman, dan pantau total sisa hutang per rekanan." 
        />

        @if (auth()->user()->hasPermission('suppliers.manage'))
            <details class="ui-panel overflow-hidden group">
                <summary class="ui-panel-header cursor-pointer select-none">
                    <div class="flex items-center gap-2">
                        <div class="flex size-7 items-center justify-center rounded-lg bg-accent/10 text-accent">
                            <flux:icon.plus class="size-4" />
                        </div>
                        <h2 class="text-sm font-bold text-zinc-950">+ Daftarkan Pemasok / Distributor Baru</h2>
                    </div>
                    <span class="text-xs font-semibold text-accent group-open:rotate-180 transition-transform">
                        Buka Formulir
                    </span>
                </summary>

                <form method="POST" action="{{ route('suppliers.store') }}" class="p-5 space-y-4 border-t border-zinc-100 bg-cream-50/40">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="ui-label" for="name">Nama Pemasok / Agen / PT</label>
                            <input id="name" name="name" class="ui-input text-base font-medium" placeholder="Contoh: CV Sumber Makmur" value="{{ old('name') }}" required maxlength="180">
                        </div>
                        <div>
                            <label class="ui-label" for="phone">Nomor Telepon / WhatsApp Sales</label>
                            <input id="phone" name="phone" class="ui-input" placeholder="Contoh: 0812-3456-7890" value="{{ old('phone') }}" maxlength="40">
                        </div>
                        <div>
                            <label class="ui-label" for="address">Alamat Gudang / Kantor Pemasok</label>
                            <textarea id="address" name="address" class="ui-textarea" placeholder="Alamat pengiriman / penagihan..." maxlength="1000">{{ old('address') }}</textarea>
                        </div>
                        <div>
                            <label class="ui-label" for="notes">Catatan Khusus (Jadwal Pengiriman / Ketentuan)</label>
                            <textarea id="notes" name="notes" class="ui-textarea" placeholder="Contoh: Pengiriman setiap hari Selasa & Kamis..." maxlength="1000">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800 font-medium">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="pt-2">
                        <button class="ui-btn ui-btn-primary font-bold text-xs" type="submit">
                            Simpan Data Pemasok
                        </button>
                    </div>
                </form>
            </details>
        @endif

        <!-- Filter & Search Toolbar -->
        <section class="ui-panel p-4 sm:p-5">
            <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-zinc-400">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        name="search" 
                        class="ui-input pl-11" 
                        value="{{ request('search') }}" 
                        placeholder="Cari nama pemasok atau nomor telepon..."
                    >
                </div>

                <div class="flex items-center gap-2">
                    <select name="status" class="ui-select sm:w-44 text-xs">
                        <option value="">Semua Status</option>
                        <option value="active" @selected(request('status') === 'active')>Aktif</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                    </select>

                    <button class="ui-btn ui-btn-primary text-xs font-bold" type="submit">
                        Terapkan
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('suppliers.index') }}" wire:navigate class="ui-btn text-xs font-semibold">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </section>

        @if ($suppliers->isEmpty())
            <x-ui.state-frame emptyTitle="Belum Ada Pemasok Terdaftar" emptyDescription="Daftarkan data pemasok toko sebelum mencatat faktur pembelian barang masuk.">
                @if (auth()->user()->hasPermission('suppliers.manage'))
                    <a class="ui-btn ui-btn-primary text-xs font-bold" href="#name">
                        + Tambah Pemasok Sekarang
                    </a>
                @endif
            </x-ui.state-frame>
        @else
            <!-- Grid Kartu Pemasok -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($suppliers as $supplier)
                    <a href="{{ route('suppliers.show', $supplier) }}" wire:navigate class="ui-panel group flex flex-col justify-between p-5 transition-all hover:border-accent hover:shadow-md">
                        <div>
                            <div class="flex items-start justify-between">
                                <div class="flex size-12 items-center justify-center rounded-2xl bg-accent text-sm font-extrabold text-white shadow-xs group-hover:scale-105 transition-transform">
                                    {{ str($supplier->name)->explode(' ')->map(fn ($word) => str($word)->substr(0, 1))->take(2)->join('') }}
                                </div>
                                <x-ui.status :value="$supplier->is_active ? 'Aktif' : 'Nonaktif'" />
                            </div>

                            <h2 class="mt-4 text-base font-bold text-zinc-950 group-hover:text-accent">{{ $supplier->name }}</h2>
                            <p class="mt-1 text-xs text-zinc-500 flex items-center gap-1.5">
                                <svg class="size-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>{{ $supplier->phone ?: 'Nomor telepon belum diisi' }}</span>
                            </p>
                            <p class="mt-2 text-xs leading-relaxed text-zinc-600 line-clamp-2">
                                {{ $supplier->address ?: 'Alamat belum dilengkapi' }}
                            </p>
                        </div>

                        <div class="mt-5 border-t border-zinc-100 pt-3 flex items-baseline justify-between">
                            <span class="text-xs text-zinc-500 font-medium">Sisa Hutang Toko</span>
                            <strong class="text-sm font-extrabold text-zinc-950">
                                Rp{{ number_format((int) ($supplier->open_balance ?? 0), 0, ',', '.') }}
                            </strong>
                        </div>
                    </a>
                @endforeach
            </div>

            <div>
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</x-layouts::app>
