<x-layouts::app :title="__('Transaksi Kasir Tertahan')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Operasional Kasir" 
            title="Daftar Transaksi Tertahan" 
            description="Lanjutkan kembali transaksi pelanggan yang sempat ditunda atau batalkan jika tidak lagi diperlukan."
        >
            <x-slot:actions>
                <a class="ui-btn ui-btn-primary" href="{{ route('pos.index') }}" wire:navigate>
                    <flux:icon.arrow-left class="size-4" /> 
                    <span>Kembali ke Kasir</span>
                </a>
            </x-slot:actions>
        </x-ui.page-header>

        <x-ui.state-frame emptyTitle="Tidak Ada Transaksi yang Ditahan" emptyDescription="Transaksi kasir yang ditunda saat melayani pembeli akan tersimpan dan tampil di daftar ini.">
            <div class="ui-panel divide-y divide-zinc-100 overflow-hidden">
                @foreach ([['TRT-001', 'Rina Safitri', '10.14', '4 item', 'Rp186.500'], ['TRT-002', 'Dedi Pratama', '12.47', '2 item', 'Rp67.000']] as [$number, $cashier, $time, $items, $total])
                    <article class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between hover:bg-cream-50/50 transition-colors">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h2 class="text-base font-bold text-zinc-950">{{ $number }}</h2>
                                <x-ui.status value="Tertahan" />
                            </div>
                            <p class="mt-1 text-sm text-zinc-600">
                                Kasir: <strong class="text-zinc-800">{{ $cashier }}</strong> &bull; Pukul {{ $time }} WIB &bull; {{ $items }}
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-lg font-extrabold text-zinc-950 sm:mr-3">{{ $total }}</span>
                            <button type="button" class="ui-btn text-xs font-semibold hover:border-rose-400 hover:text-rose-700">
                                Batalkan
                            </button>
                            <a class="ui-btn ui-btn-primary text-xs font-bold" href="{{ route('pos.index') }}" wire:navigate>
                                Lanjutkan Transaksi
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </x-ui.state-frame>
    </div>
</x-layouts::app>
