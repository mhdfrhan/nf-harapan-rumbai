<x-layouts::app :title="__('Transaksi tertahan')">
    <div class="ui-page">
        <x-ui.page-header eyebrow="Kasir" title="Transaksi tertahan" description="Lanjutkan transaksi yang ditunda atau batalkan setelah memastikan transaksi tidak lagi diperlukan.">
            <x-slot:actions><a class="ui-btn ui-btn-primary" href="{{ route('pos.index') }}"><flux:icon.arrow-left class="size-4" /> Kembali ke kasir</a></x-slot:actions>
        </x-ui.page-header>
        <x-ui.state-frame emptyTitle="Tidak ada transaksi tertahan" emptyDescription="Transaksi yang ditahan dari halaman kasir akan tampil di sini.">
            <div class="ui-panel divide-y divide-zinc-300">
                @foreach ([['TRT-001', 'Rina Safitri', '10.14', '4 item', 'Rp186.500'], ['TRT-002', 'Dedi Pratama', '12.47', '2 item', 'Rp67.000']] as [$number, $cashier, $time, $items, $total])
                    <article class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
                        <div><div class="flex items-center gap-2"><h2 class="font-semibold text-zinc-900">{{ $number }}</h2><x-ui.status value="Tertahan" /></div><p class="mt-2 text-sm text-zinc-600">{{ $cashier }} pada pukul {{ $time }} · {{ $items }}</p></div>
                        <div class="flex items-center gap-3"><strong class="mr-2 text-zinc-900">{{ $total }}</strong><button type="button" class="ui-btn">Batalkan</button><a class="ui-btn ui-btn-primary" href="{{ route('pos.index') }}">Lanjutkan</a></div>
                    </article>
                @endforeach
            </div>
        </x-ui.state-frame>
    </div>
</x-layouts::app>
