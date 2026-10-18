<x-layouts::app :title="__('Ringkasan karyawan')">
    <div class="ui-page">
        <x-ui.page-header eyebrow="Ringkasan karyawan" title="Mulai pekerjaan hari ini" description="Akses cepat untuk melayani pelanggan dan mencatat kegiatan operasional toko." />

        <section class="grid gap-4 lg:grid-cols-3" aria-label="Aksi utama">
            @foreach (collect([
                auth()->user()->hasPermission('sales.create') ? [route('pos.index'), 'shopping-cart', 'Buka kasir', 'Cari barang, atur jumlah, lalu selesaikan transaksi pelanggan.'] : null,
                auth()->user()->hasPermission('stock.view') ? [route('inventory.index'), 'archive-box', 'Periksa stok', 'Lihat stok tersedia dan produk yang mulai menipis.'] : null,
                auth()->user()->hasPermission('purchases.create') ? [route('receipts.create'), 'truck', 'Catat barang masuk', 'Masukkan barang yang baru diterima dari pemasok.'] : null,
            ])->filter() as [$href, $icon, $title, $description])
                <a href="{{ $href }}" class="ui-panel group flex min-h-44 flex-col justify-between p-5 hover:border-zinc-500">
                    <div class="flex size-11 items-center justify-center rounded-[10px] bg-[#e2ece6] text-accent"><flux:icon :name="$icon" class="size-5" /></div>
                    <div class="mt-8">
                        <h2 class="font-semibold text-zinc-900 group-hover:text-accent">{{ $title }}</h2>
                        <p class="mt-1 text-sm leading-6 text-zinc-600">{{ $description }}</p>
                    </div>
                </a>
            @endforeach
        </section>

        <section class="ui-panel" aria-labelledby="staff-activity">
            <div class="ui-panel-header">
                <div><h2 id="staff-activity" class="font-semibold text-zinc-900">Pekerjaan saya hari ini</h2><p class="mt-1 text-sm text-zinc-600">Aktivitas yang tercatat atas nama Anda.</p></div>
            </div>
            <div class="grid gap-3 p-4 sm:grid-cols-3 sm:p-5">
                <div class="rounded-[10px] bg-cream-100 p-4"><p class="text-sm text-zinc-600">Transaksi dilayani</p><p class="mt-2 text-2xl font-semibold">{{ $staffMetrics['count'] }}</p></div>
                <div class="rounded-[10px] bg-cream-100 p-4"><p class="text-sm text-zinc-600">Nilai penjualan</p><p class="mt-2 text-2xl font-semibold">Rp{{ number_format($staffMetrics['total'], 0, ',', '.') }}</p></div>
                <div class="rounded-[10px] bg-cream-100 p-4"><p class="text-sm text-zinc-600">Status</p><p class="mt-2 text-lg font-semibold">Data langsung</p></div>
            </div>
        </section>
    </div>
</x-layouts::app>
