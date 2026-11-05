<x-layouts::app :title="__('Riwayat Penjualan')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Operasional Kasir" 
            title="Riwayat Transaksi Penjualan" 
            description="Pantau seluruh struk transaksi kasir yang telah berhasil diposting atau dibatalkan."
        >
            <x-slot:actions>
                @if(auth()->user()->hasPermission('sales.create'))
                    <a class="ui-btn ui-btn-primary" href="{{ route('pos.index') }}" wire:navigate>
                        <flux:icon.shopping-cart class="size-4" />
                        <span>Transaksi Kasir Baru</span>
                    </a>
                @endif
            </x-slot:actions>
        </x-ui.page-header>

        <!-- Form Pencarian & Filter Cepat -->
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
                        placeholder="Cari nomor nota struk (contoh: SLS-2026...)"
                    >
                </div>
                <div class="flex items-center gap-2">
                    <button class="ui-btn ui-btn-primary text-xs font-bold" type="submit">
                        <span>Cari Transaksi</span>
                    </button>
                    @if(request('q'))
                        <a href="{{ route('sales.index') }}" wire:navigate class="ui-btn text-xs font-semibold">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </section>

        <!-- Tabel Riwayat Penjualan -->
        <section class="ui-panel overflow-hidden">
            <div class="ui-table-wrap">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nomor Struk</th>
                            <th>Waktu & Tanggal</th>
                            <th>Petugas Kasir</th>
                            <th>Banyak Item</th>
                            <th>Total Tagihan</th>
                            <th>Status Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>
                                    <a class="font-bold text-accent hover:underline flex items-center gap-1.5" href="{{ route('sales.show', $sale) }}" wire:navigate>
                                        <span>{{ $sale->number }}</span>
                                    </a>
                                </td>
                                <td class="whitespace-nowrap font-medium text-zinc-600">
                                    {{ $sale->sold_at->translatedFormat('d M Y, H.i') }} WIB
                                </td>
                                <td>
                                    <span class="font-semibold text-zinc-900">{{ $sale->cashier?->name ?? 'Kasir Toko' }}</span>
                                </td>
                                <td>
                                    <span class="rounded-lg bg-zinc-100 px-2 py-0.5 text-xs font-bold text-zinc-700">
                                        {{ $sale->items_count }} item
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-base font-extrabold text-zinc-950">
                                        Rp{{ number_format($sale->total_rupiah, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <x-ui.status :value="match($sale->status->value){'posted'=>'Diposting','voided'=>'Dibatalkan',default=>str($sale->status->value)->title()}"/>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center text-zinc-500">
                                    <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400 mb-2">
                                        <flux:icon.receipt-percent class="size-6" />
                                    </div>
                                    <p class="font-semibold text-zinc-700 text-sm">Belum Ada Transaksi Penjualan</p>
                                    <p class="text-xs text-zinc-400 mt-1">Transaksi yang diselesaikan di kasir akan otomatis tampil di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $sales->links() }}
        </div>
    </div>
</x-layouts::app>
