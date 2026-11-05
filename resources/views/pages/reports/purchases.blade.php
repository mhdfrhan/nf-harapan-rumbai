<x-layouts::app :title="__('Laporan Pembelian Barang')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Analisis Usaha" 
            title="Laporan Pembelian Barang Masuk" 
            description="Rekapitulasi seluruh faktur belanja dan penerimaan barang dari pemasok pada periode tanggal yang dipilih." 
        />

        @include('pages.reports._layout')

        <section class="ui-panel overflow-hidden">
            <div class="ui-panel-header">
                <div>
                    <h3 class="font-bold text-zinc-950 text-base">Daftar Penerimaan Barang dari Pemasok</h3>
                    <p class="text-xs text-zinc-500 mt-0.5">Faktur belanja yang menambah stok toko.</p>
                </div>
            </div>

            <div class="ui-table-wrap border-0 shadow-none">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nomor Dokumen</th>
                            <th>Tanggal Terima</th>
                            <th>Nama Pemasok / Distributor</th>
                            <th>Total Belanja</th>
                            <th>Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $receipt)
                            <tr>
                                <td>
                                    <a class="font-bold text-accent hover:underline flex items-center gap-1" href="{{ route('receipts.show', $receipt) }}" wire:navigate>
                                        <span>{{ $receipt->number }}</span>
                                    </a>
                                </td>
                                <td class="whitespace-nowrap font-medium text-zinc-600">
                                    {{ $receipt->received_on->translatedFormat('d M Y') }}
                                </td>
                                <td>
                                    <strong class="text-sm font-bold text-zinc-950 block">{{ $receipt->supplier?->name }}</strong>
                                </td>
                                <td>
                                    <strong class="text-sm font-extrabold text-zinc-950">
                                        Rp{{ number_format($receipt->total_rupiah, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <x-ui.status :value="match(strtolower($receipt->payment_state)){
                                        'paid' => 'Lunas',
                                        'partial' => 'Dibayar Sebagian',
                                        'unpaid' => 'Belum Lunas',
                                        default => str($receipt->payment_state)->replace('_', ' ')->title()
                                    }" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-zinc-500 text-sm">
                                    Tidak ada data penerimaan barang pada periode tanggal yang dipilih.
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
