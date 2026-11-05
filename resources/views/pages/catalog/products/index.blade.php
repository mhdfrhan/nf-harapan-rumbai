<x-layouts::app :title="__('Katalog Produk')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Katalog Barang" 
            title="Daftar Produk Dagangan" 
            description="Kelola nama produk, kode SKU, kategori, satuan kemasan, harga jual eceran/grosir, dan status aktif barang."
        >
            <x-slot:actions>
                @if(auth()->user()->hasPermission('products.create'))
                    <a class="ui-btn ui-btn-primary" href="{{ route('catalog.products.create') }}" wire:navigate>
                        <flux:icon.plus class="size-4" />
                        <span>Tambah Produk Baru</span>
                    </a>
                @endif
            </x-slot:actions>
        </x-ui.page-header>

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

        @if(session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
                {{ session('status') }}
            </div>
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
                        class="ui-input pl-11" 
                        id="q" 
                        name="q" 
                        value="{{ request('q') }}" 
                        placeholder="Cari berdasarkan nama produk, kode SKU, atau barcode..."
                    >
                </div>

                <div class="flex items-center gap-2">
                    <button class="ui-btn ui-btn-primary text-xs font-bold" type="submit">
                        <span>Cari Produk</span>
                    </button>
                    @if(request('q'))
                        <a href="{{ route('catalog.products.index') }}" wire:navigate class="ui-btn text-xs font-semibold">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </section>

        <!-- Tabel Daftar Produk -->
        <section class="ui-panel overflow-hidden">
            <div class="ui-table-wrap">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nama & SKU Produk</th>
                            <th>Kategori</th>
                            <th>Satuan Dasar</th>
                            <th>Harga Jual (Eceran)</th>
                            <th>Stok Fisik Toko</th>
                            <th>Status Barang</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php($stock = (float)($product->stockBalance?->quantity_on_hand ?? 0))
                            @php($price = $product->saleUnits->flatMap->prices->where('price_type', \App\Domain\Catalog\Enums\PriceType::Retail)->first())
                            <tr>
                                <td>
                                    <strong class="text-sm font-bold text-zinc-950 block">{{ $product->name }}</strong>
                                    <span class="text-xs font-mono text-zinc-500">{{ $product->sku }}</span>
                                </td>
                                <td>
                                    <span class="rounded-lg bg-zinc-100 px-2.5 py-1 text-xs font-medium text-zinc-700">
                                        {{ $product->category?->name ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="font-medium text-zinc-700">
                                    {{ $product->baseUnit?->code ?? '-' }}
                                </td>
                                <td>
                                    @if($price)
                                        <strong class="text-sm font-extrabold text-accent">
                                            Rp{{ number_format($price->amount_rupiah, 0, ',', '.') }}
                                        </strong>
                                    @else
                                        <span class="text-xs text-zinc-400 italic">Belum diatur</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-1.5">
                                        <strong class="text-sm font-bold {{ $stock <= (float)$product->low_stock_threshold ? 'text-amber-800' : 'text-zinc-900' }}">
                                            {{ rtrim(rtrim(number_format($stock, 4, ',', '.'), '0'), ',') }}
                                        </strong>
                                        <span class="text-xs text-zinc-500">{{ $product->baseUnit?->code }}</span>
                                    </div>
                                </td>
                                <td>
                                    <x-ui.status :value="$product->is_active ? 'Aktif' : 'Nonaktif'" />
                                </td>
                                <td class="text-right">
                                    @if(auth()->user()->hasPermission('products.update'))
                                        <a class="ui-btn text-xs font-semibold py-1.5 px-3" href="{{ route('catalog.products.edit', $product) }}" wire:navigate>
                                            Ubah
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-10 text-center text-zinc-500">
                                    <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400 mb-2">
                                        <flux:icon.archive-box class="size-6" />
                                    </div>
                                    <p class="font-semibold text-zinc-700 text-sm">Belum Ada Produk Terdaftar</p>
                                    <p class="text-xs text-zinc-400 mt-1">Daftarkan produk pertama toko Anda menggunakan tombol Tambah Produk di atas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $products->links() }}
        </div>
    </div>
</x-layouts::app>
