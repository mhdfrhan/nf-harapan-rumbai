<x-layouts::app :title="__('Buku Mutasi Stok')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Gudang & Inventaris" 
            title="Buku Catatan Mutasi Stok" 
            description="Buku besar riwayat keluar masuk barang gudang secara transparan dan permanen (tidak dapat dimanipulasi atau dihapus)."
        >
            <x-slot:actions>
                <a class="ui-btn font-semibold text-xs" href="{{ route('inventory.index') }}" wire:navigate>
                    <flux:icon.arrow-left class="size-4" />
                    <span>Kembali ke Stok Fisik</span>
                </a>
            </x-slot:actions>
        </x-ui.page-header>

        <!-- Tabel Riwayat Mutasi -->
        <section class="ui-panel overflow-hidden">
            <div class="ui-table-wrap">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Waktu & Tanggal</th>
                            <th>Nama Produk Barang</th>
                            <th>Jenis Aktivitas Mutasi</th>
                            <th>Perubahan Jumlah</th>
                            <th>Saldo Stok Akhir</th>
                            <th>Dokumen Referensi</th>
                            <th>Petugas / Pelaku</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                            @php($delta = (float)$movement->quantity_delta)
                            <tr>
                                <td class="whitespace-nowrap font-medium text-zinc-600">
                                    {{ $movement->occurred_at->translatedFormat('d M Y, H.i') }} WIB
                                </td>
                                <td>
                                    <strong class="text-sm font-bold text-zinc-950 block">{{ $movement->product?->name }}</strong>
                                    <span class="text-xs font-mono text-zinc-500">{{ $movement->product?->sku }}</span>
                                </td>
                                <td>
                                    <span class="rounded-lg bg-zinc-100 px-2.5 py-1 text-xs font-semibold text-zinc-800">
                                        {{ match($movement->movement_type->value){
                                            'sale' => 'Penjualan Kasir',
                                            'receipt' => 'Penerimaan Pemasok',
                                            'adjustment' => 'Penyesuaian Opname',
                                            'void_sale' => 'Pembatalan Kasir',
                                            default => str($movement->movement_type->value)->replace('_', ' ')->title()
                                        } }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-sm font-extrabold {{ $delta > 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $delta > 0 ? '+' : '' }}{{ rtrim(rtrim(number_format($delta, 4, ',', '.'), '0'), ',') }}
                                    </strong>
                                    <span class="text-xs text-zinc-500">{{ $movement->product?->baseUnit?->code }}</span>
                                </td>
                                <td>
                                    <strong class="text-sm font-bold text-zinc-900">
                                        {{ rtrim(rtrim(number_format((float)$movement->balance_after, 4, ',', '.'), '0'), ',') }}
                                    </strong>
                                    <span class="text-xs text-zinc-500">{{ $movement->product?->baseUnit?->code }}</span>
                                </td>
                                <td class="text-xs font-mono text-zinc-600">
                                    <span class="bg-zinc-100 px-2 py-0.5 rounded">
                                        {{ $movement->reference_type }} #{{ $movement->reference_id }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-medium text-zinc-800 text-sm">
                                        {{ $movement->actor?->name ?? 'Sistem' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-10 text-center text-zinc-500">
                                    <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400 mb-2">
                                        <flux:icon.arrows-right-left class="size-6" />
                                    </div>
                                    <p class="font-semibold text-zinc-700 text-sm">Belum Ada Catatan Mutasi Stok</p>
                                    <p class="text-xs text-zinc-400 mt-1">Seluruh arus masuk dan keluar barang dagangan akan otomatis dicatat di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $movements->links() }}
        </div>
    </div>
</x-layouts::app>
