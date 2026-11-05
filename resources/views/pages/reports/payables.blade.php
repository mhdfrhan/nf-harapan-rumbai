<x-layouts::app :title="__('Laporan Hutang Pemasok')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Analisis Usaha" 
            title="Laporan Hutang Usaha Toko" 
            description="Posisi kewajiban dan tagihan belanja tempo ke pemasok berdasarkan urutan tanggal jatuh tempo." 
        />

        @include('pages.reports._layout')

        <section class="ui-panel overflow-hidden">
            <div class="ui-panel-header">
                <div>
                    <h3 class="font-bold text-zinc-950 text-base">Rincian Tagihan Hutang Tempo Pemasok</h3>
                    <p class="text-xs text-zinc-500 mt-0.5">Pantau sisa tagihan yang harus segera dilunasi ke supplier.</p>
                </div>
            </div>

            <div class="ui-table-wrap border-0 shadow-none">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nomor Hutang</th>
                            <th>Nama Pemasok</th>
                            <th>Pokok Belanja Awal</th>
                            <th>Sudah Disetor</th>
                            <th>Sisa Hutang Toko</th>
                            <th>Jatuh Tempo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $payable)
                            <tr>
                                <td>
                                    <a class="font-bold text-accent hover:underline flex items-center gap-1" href="{{ route('payables.show', $payable) }}" wire:navigate>
                                        <span>{{ $payable->number }}</span>
                                    </a>
                                </td>
                                <td>
                                    <strong class="text-sm font-bold text-zinc-950 block">{{ $payable->supplier?->name }}</strong>
                                </td>
                                <td>Rp{{ number_format($payable->principal_rupiah, 0, ',', '.') }}</td>
                                <td class="text-emerald-700 font-semibold">Rp{{ number_format($payable->paid_rupiah, 0, ',', '.') }}</td>
                                <td>
                                    <strong class="text-sm font-extrabold {{ $payable->balance_rupiah > 0 ? 'text-amber-800' : 'text-zinc-500' }}">
                                        Rp{{ number_format($payable->balance_rupiah, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="whitespace-nowrap font-medium text-zinc-600">
                                    {{ $payable->due_on ? $payable->due_on->translatedFormat('d M Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-zinc-500 text-sm">
                                    Tidak ada tagihan hutang pemasok pada periode tanggal yang dipilih.
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
