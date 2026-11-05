<x-layouts::app :title="__('Satuan Ukuran Barang')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Katalog Barang" 
            title="Daftar Satuan Ukuran" 
            description="Satuan dasar dan kemasan untuk menentukan takaran, jumlah pecahan, dan konversi barang dagangan."
        />

        <!-- Subnav Navigasi Tab Katalog -->
        <nav class="flex gap-2 overflow-x-auto border-b border-zinc-200 pb-2" aria-label="Menu Katalog">
            <a href="{{ route('catalog.products.index') }}" wire:navigate class="min-h-10 shrink-0 rounded-xl px-4 py-2 text-xs sm:text-sm font-bold transition-all {{ request()->routeIs('catalog.products.*') ? 'bg-accent text-white shadow-xs' : 'text-zinc-600 hover:text-zinc-950 hover:bg-cream-200/60' }}">
                Daftar Produk
            </a>
            <a href="{{ route('catalog.categories.index') }}" wire:navigate class="min-h-10 shrink-0 rounded-xl px-4 py-2 text-xs sm:text-sm font-bold transition-all {{ request()->routeIs('catalog.categories.*') ? 'bg-accent text-white shadow-xs' : 'text-zinc-600 hover:text-zinc-950 hover:bg-cream-200/60' }}">
                Kategori Produk
            </a>
            <a href="{{ route('catalog.units.index') }}" wire:navigate class="min-h-10 shrink-0 rounded-xl px-4 py-2 text-xs sm:text-sm font-bold transition-all {{ request()->routeIs('catalog.units.*') ? 'bg-accent text-white shadow-xs' : 'text-zinc-600 hover:text-zinc-950 hover:bg-cream-200/60' }}">
                Satuan Ukuran
            </a>
        </nav>

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Daftar Satuan yang Terdaftar -->
            <section class="ui-panel overflow-hidden">
                <div class="ui-panel-header">
                    <h2 class="text-base font-bold text-zinc-950">Daftar Satuan Aktif</h2>
                    <span class="text-xs text-zinc-500">{{ $units->count() }} satuan</span>
                </div>
                <div class="p-5">
                    <ul class="divide-y divide-zinc-100">
                        @forelse($units as $unit)
                            <li class="flex items-center justify-between py-3.5">
                                <div>
                                    <strong class="font-bold text-zinc-950 font-mono text-sm">{{ $unit->code }}</strong>
                                    <span class="text-sm text-zinc-700 ml-1.5">— {{ $unit->name }}</span>
                                </div>
                                <span class="rounded-lg bg-zinc-100 px-2.5 py-1 text-xs text-zinc-600 font-medium">
                                    {{ match($unit->dimension){'count'=>'Jumlah','mass'=>'Berat','volume'=>'Volume','package'=>'Kemasan',default=>$unit->dimension} }}, {{ $unit->decimal_scale }} desimal
                                </span>
                            </li>
                        @empty
                            <li class="py-6 text-center text-sm text-zinc-500">
                                Belum ada satuan barang yang terdaftar.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </section>

            <!-- Formulir Tambah Satuan -->
            @if(auth()->user()->hasPermission('product_units.manage'))
                <section class="ui-panel overflow-hidden">
                    <div class="ui-panel-header">
                        <h2 class="text-base font-bold text-zinc-950">Tambah Satuan Ukuran Baru</h2>
                    </div>
                    <form method="POST" action="{{ route('catalog.units.store') }}" class="p-5 space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="ui-label" for="code">Kode Singkatan</label>
                                <input id="code" name="code" class="ui-input uppercase font-mono" placeholder="Contoh: PCS, KG, DUS" required>
                            </div>
                            <div>
                                <label class="ui-label" for="unit_name">Nama Lengkap Satuan</label>
                                <input id="unit_name" name="name" class="ui-input" placeholder="Contoh: Pieces, Kilogram" required>
                            </div>
                            <div>
                                <label class="ui-label" for="dimension">Jenis Dimensi</label>
                                <select id="dimension" name="dimension" class="ui-select">
                                    <option value="count">Jumlah / Butir (Pcs, Bks)</option>
                                    <option value="mass">Berat / Massa (Kg, Gram)</option>
                                    <option value="volume">Isi / Volume (Liter, Ml)</option>
                                    <option value="package">Kemasan (Dus, Pack, Slop)</option>
                                </select>
                            </div>
                            <div>
                                <label class="ui-label" for="decimal_scale">Presisi Angka Desimal (Koma)</label>
                                <input id="decimal_scale" name="decimal_scale" type="number" min="0" max="4" value="0" class="ui-input">
                                <p class="mt-1 text-xs text-zinc-500">Isi 0 untuk bilangan bulat, atau 2-4 untuk timbangan berat.</p>
                            </div>
                        </div>
                        <div class="pt-2">
                            <button class="ui-btn ui-btn-primary font-bold text-xs" type="submit">
                                + Daftarkan Satuan Baru
                            </button>
                        </div>
                    </form>
                </section>
            @endif
        </div>
    </div>
</x-layouts::app>
