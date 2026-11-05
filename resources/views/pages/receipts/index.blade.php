<x-layouts::app :title="__('Penerimaan Barang')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Gudang & Pemasok" 
            title="Daftar Penerimaan Barang Masuk" 
            description="Catatan faktur dan surat jalan barang dagangan yang diterima dari pemasok untuk menambah saldo stok gudang."
        >
            <x-slot:actions>
                @if(auth()->user()->hasPermission('purchases.create'))
                    <a class="ui-btn ui-btn-primary" href="{{ route('receipts.create') }}" wire:navigate>
                        <flux:icon.plus class="size-4" />
                        <span>Catat Barang Masuk</span>
                    </a>
                @endif
            </x-slot:actions>
        </x-ui.page-header>

        <!-- Tabel Penerimaan Barang -->
        <section class="ui-panel overflow-hidden">
            <div class="ui-table-wrap">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nomor Dokumen</th>
                            <th>Tanggal Masuk</th>
                            <th>Nama Pemasok / Distributor</th>
                            <th>Banyak Item</th>
                            <th>Total Tagihan Belanja</th>
                            <th>Status Pembayaran</th>
                            <th>Status Penerimaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receipts as $receipt)
                            <tr>
                                <td>
                                    <a class="text-accent font-bold hover:underline flex items-center gap-1" href="{{ route('receipts.show', $receipt) }}" wire:navigate>
                                        <span>{{ $receipt->number }}</span>
                                    </a>
                                </td>
                                <td class="whitespace-nowrap font-medium text-zinc-600">
                                    {{ $receipt->received_on->translatedFormat('d M Y') }}
                                </td>
                                <td>
                                    <strong class="text-sm font-bold text-zinc-900 block">{{ $receipt->supplier?->name }}</strong>
                                </td>
                                <td>
                                    <span class="rounded-lg bg-zinc-100 px-2 py-0.5 text-xs font-bold text-zinc-700">
                                        {{ $receipt->items_count }} item
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-base font-extrabold text-zinc-950">
                                        Rp{{ number_format($receipt->total_rupiah, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <x-ui.status :value="match(strtolower($receipt->payment_state)){
                                        'paid' => 'Lunas',
                                        'partial' => 'Dibayar Sebagian',
                                        'unpaid' => 'Belum Lunas',
                                        default => str($receipt->payment_state)->replace('_', ' ')->title()
                                    }"/>
                                </td>
                                <td>
                                    <x-ui.status :value="match($receipt->status->value){
                                        'posted' => 'Diposting',
                                        'voided' => 'Dibatalkan',
                                        default => str($receipt->status->value)->replace('_', ' ')->title()
                                    }"/>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-10 text-center text-zinc-500">
                                    <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400 mb-2">
                                        <flux:icon.truck class="size-6" />
                                    </div>
                                    <p class="font-semibold text-zinc-700 text-sm">Belum Ada Catatan Penerimaan Barang</p>
                                    <p class="text-xs text-zinc-400 mt-1">Catat belanja stok masuk dari pemasok untuk menambah saldo persediaan barang toko.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $receipts->links() }}
        </div>
    </div>
</x-layouts::app>
