<x-layouts::app :title="__('Stok Persediaan Barang')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Gudang & Inventaris" 
            title="Stok Fisik Barang Dagangan" 
            description="Saldo persediaan terkini di gudang toko yang otomatis berkurang saat kasir melayani transaksi, atau bertambah saat penerimaan barang baru."
        >
            <x-slot:actions>
                <a class="ui-btn font-semibold text-xs" href="{{ route('inventory.movements') }}" wire:navigate>
                    <flux:icon.arrows-right-left class="size-4" />
                    <span>Buku Mutasi Stok</span>
                </a>
                @if(auth()->user()->hasPermission('stock.adjust'))
                    <a class="ui-btn ui-btn-primary font-bold text-xs" href="{{ route('inventory.adjustments.create') }}" wire:navigate>
                        <flux:icon.plus class="size-4" />
                        <span>Penyesuaian Stok (Opname)</span>
                    </a>
                @endif
            </x-slot:actions>
        </x-ui.page-header>

        <!-- Form Pencarian Stok -->
        <section class="ui-panel p-4 sm:p-5">
            <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-zinc-400">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        name="q" 
                        class="ui-input pl-11" 
                        value="{{ request('q') }}" 
                        placeholder="Cari berdasarkan nama produk atau kode SKU..."
                    >
                </div>
                <div class="flex items-center gap-2">
                    <button class="ui-btn ui-btn-primary text-xs font-bold" type="submit">
                        <span>Cari Stok</span>
                    </button>
                    @if(request('q'))
                        <a href="{{ route('inventory.index') }}" wire:navigate class="ui-btn text-xs font-semibold">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </section>

        <!-- Tabel Saldo Stok Barang -->
        <section class="ui-panel overflow-hidden">
            <div class="ui-table-wrap">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nama Produk & SKU</th>
                            <th>Kategori</th>
                            <th>Saldo Stok Terkini</th>
                            <th>Batas Minimal (Peringatan)</th>
                            <th>Kondisi Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php($stock = (float)($product->stockBalance?->quantity_on_hand ?? 0))
                            @php($isLow = $stock <= (float)$product->low_stock_threshold)
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
                                <td>
                                    <strong class="text-base font-extrabold {{ $isLow ? 'text-amber-800' : 'text-zinc-950' }}">
                                        {{ rtrim(rtrim(number_format($stock, 4, ',', '.'), '0'), ',') }}
                                    </strong>
                                    <span class="text-xs text-zinc-500 ml-1">{{ $product->baseUnit?->code }}</span>
                                </td>
                                <td>
                                    <span class="text-sm font-medium text-zinc-600">
                                        {{ rtrim(rtrim(number_format((float)$product->low_stock_threshold, 4, ',', '.'), '0'), ',') }} {{ $product->baseUnit?->code }}
                                    </span>
                                </td>
                                <td>
                                    <x-ui.status :value="$isLow ? 'Stok Menipis' : 'Aman'"/>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-zinc-500">
                                    <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400 mb-2">
                                        <flux:icon.arrows-right-left class="size-6" />
                                    </div>
                                    <p class="font-semibold text-zinc-700 text-sm">Tidak Ditemukan Data Persediaan</p>
                                    <p class="text-xs text-zinc-400 mt-1">Produk dagangan yang terdaftar akan otomatis tampil dengan jumlah saldo stoknya di sini.</p>
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
