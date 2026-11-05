<x-layouts::app :title="__('Beranda Kerja')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Operasional Toko" 
            title="Mulai Pekerjaan Hari Ini" 
            description="Pilih menu kerja di bawah untuk melayani transaksi pembeli atau mencatat mutasi barang toko." 
        />

        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" aria-label="Aksi Utama Karyawan">
            @foreach (collect([
                auth()->user()->hasPermission('sales.create') ? [route('pos.index'), 'shopping-cart', 'Buka Mesin Kasir (POS)', 'Cari barang dagangan, atur jumlah, dan cetak struk pembayaran pelanggan.', 'bg-accent text-white'] : null,
                auth()->user()->hasPermission('stock.view') ? [route('inventory.index'), 'archive-box', 'Periksa Stok Barang', 'Lihat saldo stok fisik yang tersedia dan produk yang mulai menipis.', 'bg-emerald-50 text-emerald-700'] : null,
                auth()->user()->hasPermission('purchases.create') ? [route('receipts.create'), 'truck', 'Catat Barang Masuk', 'Masukkan faktur/nota barang yang baru diterima dari pemasok.', 'bg-sky-50 text-sky-700'] : null,
            ])->filter() as [$href, $icon, $title, $description, $iconStyle])
                <a href="{{ $href }}" wire:navigate class="ui-panel group flex min-h-48 flex-col justify-between p-6 transition-all hover:border-accent hover:shadow-md">
                    <div class="flex size-12 items-center justify-center rounded-xl {{ $iconStyle }} shadow-xs transition-transform group-hover:scale-105">
                        <flux:icon :name="$icon" class="size-6" />
                    </div>
                    <div class="mt-6">
                        <h2 class="text-base font-bold text-zinc-950 group-hover:text-accent">{{ $title }}</h2>
                        <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">{{ $description }}</p>
                    </div>
                </a>
            @endforeach
        </section>

        <section class="ui-panel" aria-labelledby="staff-activity">
            <div class="ui-panel-header">
                <div>
                    <h2 id="staff-activity" class="font-bold text-zinc-900 text-base">Pekerjaan Saya Hari Ini</h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Ringkasan transaksi dan pelayanan yang tercatat atas nama akun Anda hari ini.</p>
                </div>
            </div>
            <div class="grid gap-4 p-5 sm:grid-cols-3">
                <div class="rounded-xl border border-zinc-200/80 bg-cream-50/70 p-4">
                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Nota Dilayani</p>
                    <p class="mt-2 text-3xl font-extrabold text-zinc-950">{{ $staffMetrics['count'] }}</p>
                    <p class="mt-1 text-xs text-zinc-500">Transaksi selesai diposting</p>
                </div>
                <div class="rounded-xl border border-zinc-200/80 bg-cream-50/70 p-4">
                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Nilai Penjualan</p>
                    <p class="mt-2 text-3xl font-extrabold text-accent">Rp{{ number_format($staffMetrics['total'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-zinc-500">Uang masuk kasir hari ini</p>
                </div>
                <div class="rounded-xl border border-zinc-200/80 bg-cream-50/70 p-4">
                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Status Sinkronisasi</p>
                    <div class="mt-2 flex items-center gap-2 text-emerald-700 font-bold text-lg">
                        <span class="size-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Terhubung Langsung</span>
                    </div>
                    <p class="mt-1 text-xs text-zinc-500">Tercatat ke database toko</p>
                </div>
            </div>
        </section>
    </div>
</x-layouts::app>
