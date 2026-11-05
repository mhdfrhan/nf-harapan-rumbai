<x-layouts::app :title="__('Laporan Stok Persediaan')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Analisis Usaha" 
            title="Laporan Persediaan Stok Barang" 
            description="Saldo stok fisik terkini di toko per produk yang dihitung dalam satuan dasar inventaris." 
        />

        @include('pages.reports._layout')

        <section class="ui-panel overflow-hidden">
            <div class="ui-panel-header">
                <div>
                    <h3 class="font-bold text-zinc-950 text-base">Rincian Saldo Stok Barang di Gudang</h3>
                    <p class="text-xs text-zinc-500 mt-0.5">Pantau produk yang masih melimpah, mulai menipis, atau sudah habis.</p>
                </div>
            </div>

            <div class="ui-table-wrap border-0 shadow-none">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Kode SKU</th>
                            <th>Nama Produk Barang</th>
                            <th>Saldo Stok Terkini</th>
                            <th>Satuan Dasar</th>
                            <th>Status Ketersediaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $product)
                            @php($stock = (float) ($product->stockBalance?->quantity_on_hand ?? 0))
                            <tr>
                                <td>
                                    <span class="font-mono text-xs font-bold text-zinc-700 bg-zinc-100 px-2 py-0.5 rounded">
                                        {{ $product->sku }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="font-bold text-zinc-950 text-sm block">{{ $product->name }}</strong>
                                </td>
                                <td>
                                    <strong class="text-base font-extrabold {{ $stock <= 0 ? 'text-rose-700' : ($stock <= (float)$product->low_stock_threshold ? 'text-amber-800' : 'text-zinc-950') }}">
                                        {{ rtrim(rtrim(number_format($stock, 4, ',', '.'), '0'), ',') }}
                                    </strong>
                                </td>
                                <td class="font-medium text-zinc-600">{{ $product->baseUnit?->code }}</td>
                                <td>
                                    <x-ui.status :value="$stock <= 0 ? 'Habis' : ($stock <= (float) $product->low_stock_threshold ? 'Stok Menipis' : 'Tersedia')" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-zinc-500 text-sm">
                                    Belum ada data produk barang terdaftar.
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
