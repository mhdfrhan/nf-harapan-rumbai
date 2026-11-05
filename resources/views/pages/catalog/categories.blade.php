<x-layouts::app :title="__('Kategori Produk')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Katalog Barang" 
            title="Kategori Produk" 
            description="Pengelompokan barang dagangan untuk mempermudah pencarian di kasir dan laporan penjualan."
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
            <!-- Daftar Kategori yang Tersedia -->
            <section class="ui-panel overflow-hidden">
                <div class="ui-panel-header">
                    <h2 class="text-base font-bold text-zinc-950">Daftar Kategori Produk</h2>
                    <span class="text-xs text-zinc-500">{{ $categories->count() }} kategori</span>
                </div>
                <div class="p-5">
                    <ul class="divide-y divide-zinc-100">
                        @forelse($categories as $category)
                            <li class="flex items-center justify-between py-3.5">
                                <span class="font-bold text-zinc-900 text-sm">{{ $category->name }}</span>
                                <x-ui.status :value="$category->is_active ? 'Aktif' : 'Nonaktif'"/>
                            </li>
                        @empty
                            <li class="py-6 text-center text-sm text-zinc-500">
                                Belum ada kategori barang yang terdaftar.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </section>

            <!-- Formulir Tambah Kategori -->
            @if(auth()->user()->hasPermission('products.create'))
                <section class="ui-panel overflow-hidden">
                    <div class="ui-panel-header">
                        <h2 class="text-base font-bold text-zinc-950">Tambah Kategori Baru</h2>
                    </div>
                    <form method="POST" action="{{ route('catalog.categories.store') }}" class="p-5 space-y-4">
                        @csrf
                        <div>
                            <label class="ui-label" for="category_name">Nama Kategori</label>
                            <input id="category_name" name="name" class="ui-input" placeholder="Contoh: Makanan Ringan, Minuman, Sembako" required>
                            <p class="mt-1.5 text-xs text-zinc-500">Gunakan nama kategori yang umum dan mudah dipahami oleh kasir.</p>
                        </div>
                        <button class="ui-btn ui-btn-primary font-bold text-xs" type="submit">
                            + Tambah Kategori
                        </button>
                    </form>
                </section>
            @endif
        </div>
    </div>
</x-layouts::app>
