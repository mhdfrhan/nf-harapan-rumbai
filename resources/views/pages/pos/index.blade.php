@php($catalog = $products->flatMap(fn($p) => $p->saleUnits->map(function($u) use ($p) {
    $ret = $u->prices->firstWhere('price_type', \App\Domain\Catalog\Enums\PriceType::Retail);
    $wh = $u->prices->firstWhere('price_type', \App\Domain\Catalog\Enums\PriceType::Wholesale);
    return [
        'unit_id' => $u->id,
        'name' => $p->name,
        'sku' => $p->sku,
        'category_id' => $p->category_id,
        'unit' => $u->label,
        'conversion' => (float) $u->conversion_to_base,
        'stock' => (float) ($p->stockBalance?->quantity_on_hand ?? 0),
        'retail' => $ret?->amount_rupiah ?? 0,
        'wholesale' => $wh?->amount_rupiah,
        'minimum' => (float) ($wh?->minimum_quantity ?? PHP_INT_MAX)
    ];
}))->values())

<x-layouts::app :title="__('Mesin Kasir (POS)')">
<div class="ui-page max-w-none pb-24 md:pb-6" x-data="{
    catalog: @js($catalog),
    items: [],
    search: '',
    selectedCategory: '',
    paymentMethod: 'cash',
    received: '',
    activeTab: 'catalog',
    confirmOpen: false,
    isSubmitting: false,
    openConfirmModal() {
        if (this.items.length === 0) return;
        if (this.paymentMethod === 'cash' && this.change < 0) return;
        this.confirmOpen = true;
    },
    submitForm() {
        this.isSubmitting = true;
        this.$refs.posForm.submit();
    },
    add(p) {
        let x = this.items.find(i => i.unit_id === p.unit_id);
        if (x) {
            x.qty++;
        } else {
            this.items.push({...p, qty: 1});
        }
        if (this.paymentMethod !== 'cash') {
            this.received = this.money(this.total);
        }
    },
    updateQty(index, delta) {
        let item = this.items[index];
        if (!item) return;
        let newQty = Math.round((Number(item.qty) + delta) * 10000) / 10000;
        if (newQty <= 0) {
            this.items.splice(index, 1);
        } else {
            item.qty = newQty;
        }
        if (this.paymentMethod !== 'cash') {
            this.received = this.money(this.total);
        }
    },
    price(i) {
        return i.wholesale && i.qty >= i.minimum ? i.wholesale : i.retail;
    },
    subtotal(i) {
        return Math.round(this.price(i) * i.qty);
    },
    get totalCount() {
        return this.items.reduce((t, i) => t + Number(i.qty || 0), 0);
    },
    get total() {
        return this.items.reduce((t, i) => t + this.subtotal(i), 0);
    },
    money(v) {
        return new Intl.NumberFormat('id-ID').format(v || 0);
    },
    selectPaymentMethod(method) {
        this.paymentMethod = method;
        if (method !== 'cash') {
            this.received = this.money(this.total);
        }
    },
    get rawReceived() {
        return Number(String(this.received).replace(/\D/g, '')) || 0;
    },
    get change() {
        return this.rawReceived - this.total;
    },
    get quickCashOptions() {
        let t = this.total;
        if (t <= 0) return [];
        let opts = new Set();
        opts.add(t);

        let roundUp5k = Math.ceil(t / 5000) * 5000;
        if (roundUp5k > t) opts.add(roundUp5k);

        let roundUp10k = Math.ceil(t / 10000) * 10000;
        if (roundUp10k > t) opts.add(roundUp10k);

        let roundUp20k = Math.ceil(t / 20000) * 20000;
        if (roundUp20k > t) opts.add(roundUp20k);

        let roundUp50k = Math.ceil(t / 50000) * 50000;
        if (roundUp50k > t) opts.add(roundUp50k);

        let roundUp100k = Math.ceil(t / 100000) * 100000;
        if (roundUp100k > t) opts.add(roundUp100k);

        let denominations = [10000, 20000, 50000, 100000, 200000];
        for (let d of denominations) {
            if (d > t) opts.add(d);
        }

        return Array.from(opts).sort((a, b) => a - b).slice(0, 5);
    },
    setCashAmount(amt) {
        this.received = this.money(amt);
    }
}">

    <!-- Header Halaman Kasir -->
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-ui.page-header 
                eyebrow="Operasional Kasir" 
                title="Mesin Kasir Penjualan" 
                description="Pilih produk dagangan pembeli, hitung otomatis kembalian tunai/QRIS, lalu simpan dan cetak transaksi." 
            />
        </div>

        <!-- Tab Navigasi Tampilan Mobile (< md) -->
        <div class="flex items-center rounded-2xl bg-zinc-200/70 p-1.5 md:hidden">
            <button 
                type="button" 
                @click="activeTab = 'catalog'"
                :class="activeTab === 'catalog' ? 'bg-white text-zinc-950 font-bold shadow-xs' : 'text-zinc-600 hover:text-zinc-900 font-medium'"
                class="flex-1 rounded-xl py-2.5 text-center text-xs transition-all"
            >
                Katalog Produk
            </button>
            <button 
                type="button" 
                @click="activeTab = 'cart'"
                :class="activeTab === 'cart' ? 'bg-white text-zinc-950 font-bold shadow-xs' : 'text-zinc-600 hover:text-zinc-900 font-medium'"
                class="relative flex-1 rounded-xl py-2.5 text-center text-xs transition-all flex items-center justify-center gap-1.5"
            >
                <span>Keranjang</span>
                <template x-if="items.length > 0">
                    <span class="inline-flex size-5 items-center justify-center rounded-full bg-accent text-[11px] font-bold text-white" x-text="items.length"></span>
                </template>
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800 mb-4 text-sm font-medium">
            <strong>Transaksi belum dapat disimpan:</strong> {{ $errors->first() }}
        </div>
    @endif

    <form x-ref="posForm" method="POST" action="{{ route('pos.store') }}" class="grid gap-6 md:grid-cols-[1fr_400px] lg:grid-cols-[1fr_460px]">
        @csrf
        <input type="hidden" name="idempotency_key" value="{{ $idempotencyKey }}">

        <!-- SISI KIRI: Katalog Produk Dagangan -->
        <section 
            class="ui-panel flex flex-col overflow-hidden"
            :class="{ 'hidden md:flex': activeTab !== 'catalog' }"
        >
            <div class="border-b border-zinc-100 p-4 sm:p-5 space-y-3 bg-cream-50/50">
                <!-- Search Box -->
                <div class="relative w-full">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-zinc-400">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        class="ui-input w-full pl-11 text-base placeholder:text-zinc-400" 
                        x-model="search" 
                        placeholder="Ketik nama produk atau kode SKU..." 
                        autofocus
                    >
                </div>

                <!-- Category Chips -->
                @if(isset($categories) && $categories->count() > 0)
                    <div class="flex w-full items-center gap-2 overflow-x-auto pb-1 text-xs">
                        <button 
                            type="button"
                            @click="selectedCategory = ''"
                            :class="selectedCategory === '' ? 'bg-zinc-950 text-white font-bold shadow-xs' : 'bg-white text-zinc-700 border border-zinc-200 hover:border-zinc-400 font-semibold'"
                            class="whitespace-nowrap rounded-xl px-3.5 py-2 transition-all"
                        >
                            Semua Kategori
                        </button>
                        @foreach($categories as $cat)
                            <button 
                                type="button"
                                @click="selectedCategory = '{{ $cat->id }}'"
                                :class="selectedCategory === '{{ $cat->id }}' ? 'bg-zinc-950 text-white font-bold shadow-xs' : 'bg-white text-zinc-700 border border-zinc-200 hover:border-zinc-400 font-semibold'"
                                class="whitespace-nowrap rounded-xl px-3.5 py-2 transition-all"
                            >
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Grid -->
            <div class="p-4 sm:p-5 grid gap-4 grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 max-h-[calc(100vh-250px)] overflow-y-auto">
                <template x-for="p in catalog.filter(p => (!search || (`${p.name} ${p.sku}`).toLowerCase().includes(search.toLowerCase())) && (!selectedCategory || p.category_id == selectedCategory))" :key="p.unit_id">
                    <button 
                        type="button" 
                        @click="add(p)" 
                        class="group relative flex flex-col justify-between rounded-2xl border border-zinc-200/90 bg-white p-4 text-left shadow-xs transition-all hover:border-accent hover:shadow-md active:scale-[0.98]"
                    >
                        <!-- Top SKU & Satuan -->
                        <div>
                            <div class="flex items-center justify-between text-xs text-zinc-500">
                                <span class="font-mono text-[11px] bg-zinc-100 px-1.5 py-0.5 rounded text-zinc-600" x-text="p.sku"></span>
                                <span class="font-semibold text-zinc-600" x-text="p.unit"></span>
                            </div>
                            <strong class="mt-2 block text-sm font-bold text-zinc-950 group-hover:text-accent line-clamp-2 leading-snug" x-text="p.name"></strong>
                        </div>

                        <!-- Price & Stock -->
                        <div class="mt-4 border-t border-zinc-100 pt-2.5">
                            <div class="text-base font-extrabold text-accent" x-text="`Rp${money(p.retail)}`"></div>
                            <div class="flex items-center justify-between text-xs text-zinc-500 mt-1">
                                <span>Stok: <strong class="text-zinc-800" x-text="p.stock"></strong></span>
                                <template x-if="p.wholesale">
                                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">Grosir Tersedia</span>
                                </template>
                            </div>
                        </div>

                        <!-- Indicator Badge Jika Sudah Ada di Keranjang -->
                        <template x-if="items.find(i => i.unit_id === p.unit_id)">
                            <span class="absolute -right-2 -top-2 flex size-7 items-center justify-center rounded-full bg-accent text-xs font-extrabold text-white shadow-md" x-text="items.find(i => i.unit_id === p.unit_id).qty"></span>
                        </template>
                    </button>
                </template>
            </div>
        </section>

        <!-- SISI KANAN: Keranjang Belanja & Pembayaran -->
        <aside 
            class="ui-panel flex flex-col justify-between overflow-hidden"
            :class="{ 'hidden md:flex': activeTab !== 'cart' }"
        >
            <div>
                <!-- Cart Header -->
                <div class="border-b border-zinc-100 p-4 sm:p-5 flex items-center justify-between bg-cream-50/50">
                    <div class="flex items-center gap-2.5">
                        <div class="flex size-9 items-center justify-center rounded-xl bg-accent text-white">
                            <flux:icon.shopping-cart class="size-4" />
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-zinc-950">Keranjang Belanja</h2>
                            <span class="text-xs text-zinc-500" x-text="`${items.length} macam barang`"></span>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        class="text-xs font-semibold text-rose-600 hover:underline" 
                        @click="items=[]"
                        x-show="items.length > 0"
                    >
                        Kosongkan
                    </button>
                </div>

                <!-- Daftar Barang di Keranjang -->
                <div class="divide-y divide-zinc-100 max-h-[calc(100vh-480px)] overflow-y-auto">
                    <template x-if="items.length === 0">
                        <div class="p-10 text-center text-zinc-500">
                            <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-400">
                                <flux:icon.shopping-cart class="size-7" />
                            </div>
                            <p class="mt-3 text-sm font-bold text-zinc-800">Keranjang Masih Kosong</p>
                            <p class="mt-1 text-xs text-zinc-500 max-w-xs mx-auto">Klik produk di katalog sebelah kiri untuk memasukkan ke struk belanja pembeli.</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in items" :key="item.unit_id">
                        <div class="p-4 hover:bg-cream-50/50 transition-colors">
                            <input type="hidden" :name="`items[${index}][product_unit_id]`" :value="item.unit_id">
                            
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <strong class="block text-sm font-bold text-zinc-950 truncate" x-text="item.name"></strong>
                                    <div class="flex items-center gap-2 mt-0.5 text-xs text-zinc-500">
                                        <span x-text="item.unit"></span>
                                        <span>&bull;</span>
                                        <span class="font-medium" x-text="item.qty >= item.minimum && item.wholesale ? 'Harga Grosir' : 'Harga Eceran'"></span>
                                    </div>
                                </div>
                                <button 
                                    type="button" 
                                    @click="items.splice(index, 1)"
                                    class="text-zinc-400 hover:text-rose-600 p-1"
                                    title="Hapus barang"
                                >
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <!-- Kontrol Tombol Tambah/Kurang Kuantitas -->
                                <div class="inline-flex items-center rounded-xl border border-zinc-200 bg-white shadow-2xs">
                                    <button 
                                        type="button"
                                        @click="updateQty(index, -1)"
                                        class="flex size-8 items-center justify-center text-zinc-600 hover:bg-zinc-100 rounded-l-xl font-bold text-sm"
                                    >
                                        &minus;
                                    </button>
                                    <input 
                                        :name="`items[${index}][quantity]`" 
                                        x-model.number="item.qty" 
                                        type="number" 
                                        min="0.0001" 
                                        step="0.0001" 
                                        class="w-14 text-center text-xs font-bold text-zinc-900 focus:outline-hidden bg-transparent"
                                    >
                                    <button 
                                        type="button"
                                        @click="updateQty(index, 1)"
                                        class="flex size-8 items-center justify-center text-zinc-600 hover:bg-zinc-100 rounded-r-xl font-bold text-sm"
                                    >
                                        +
                                    </button>
                                </div>

                                <strong class="text-sm font-bold text-zinc-950" x-text="`Rp${money(subtotal(item))}`"></strong>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Panel Pembayaran & Total Tagihan -->
            <div class="border-t border-zinc-200/90 bg-cream-50/70 p-5">
                <input type="hidden" name="payment_method" :value="paymentMethod">

                <!-- Total Pembayaran -->
                <div class="flex items-baseline justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Total Tagihan</span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-accent" x-text="`Rp${money(total)}`"></div>
                    </div>
                    <span class="rounded-xl bg-accent/10 px-3 py-1 text-xs font-bold text-accent" x-text="`${totalCount} unit barang`"></span>
                </div>

                <!-- Pilihan Metode Pembayaran -->
                <div class="mt-4">
                    <label class="ui-label text-xs mb-1.5 block">Metode Pembayaran</label>
                    <div class="grid grid-cols-3 gap-2 rounded-xl bg-zinc-200/60 p-1">
                        <button 
                            type="button" 
                            @click="selectPaymentMethod('cash')"
                            :class="paymentMethod === 'cash' ? 'bg-white text-zinc-950 font-bold shadow-xs' : 'text-zinc-600 hover:text-zinc-900 font-semibold'"
                            class="flex items-center justify-center gap-1.5 rounded-lg py-2.5 text-xs transition-all"
                        >
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Tunai</span>
                        </button>
                        <button 
                            type="button" 
                            @click="selectPaymentMethod('qris')"
                            :class="paymentMethod === 'qris' ? 'bg-white text-zinc-950 font-bold shadow-xs' : 'text-zinc-600 hover:text-zinc-900 font-semibold'"
                            class="flex items-center justify-center gap-1.5 rounded-lg py-2.5 text-xs transition-all"
                        >
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <span>QRIS</span>
                        </button>
                        <button 
                            type="button" 
                            @click="selectPaymentMethod('transfer')"
                            :class="paymentMethod === 'transfer' ? 'bg-white text-zinc-950 font-bold shadow-xs' : 'text-zinc-600 hover:text-zinc-900 font-semibold'"
                            class="flex items-center justify-center gap-1.5 rounded-lg py-2.5 text-xs transition-all"
                        >
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                            <span>Transfer</span>
                        </button>
                    </div>
                </div>

                <!-- Input Nominal Tunai & Pecahan Cepat -->
                <div x-show="paymentMethod === 'cash'" class="mt-4">
                    <!-- Quick Cash Suggestion Chips -->
                    <div x-show="items.length > 0">
                        <label class="ui-label text-xs mb-1.5 block">Pilihan Uang Pas / Pecahan:</label>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="opt in quickCashOptions" :key="opt">
                                <button 
                                    type="button"
                                    @click="setCashAmount(opt)"
                                    :class="rawReceived === opt ? 'bg-zinc-950 text-white font-bold shadow-xs' : 'bg-white text-zinc-800 border border-zinc-200 hover:border-zinc-400 font-semibold'"
                                    class="rounded-xl px-3 py-1.5 text-xs transition-all"
                                >
                                    <span x-text="opt === total ? 'Uang Pas (Rp' + money(opt) + ')' : 'Rp' + money(opt)"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Input Nominal Tunai Manual -->
                    <div class="mt-3">
                        <label class="ui-label text-xs">Uang Diterima dari Pembeli (Rp)</label>
                        <input name="received_rupiah" x-model="received" inputmode="numeric" class="ui-input text-base font-bold text-zinc-900 h-11" placeholder="Masukkan jumlah uang tunai" required>
                    </div>

                    <!-- Banner Informasi Kembalian -->
                    <div x-show="items.length > 0 && received" class="mt-3 rounded-2xl p-4 border" :class="change < 0 ? 'bg-rose-50 border-rose-200' : 'bg-emerald-50 border-emerald-200'">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold" :class="change < 0 ? 'text-rose-800' : 'text-emerald-800'">
                                <span x-text="change < 0 ? 'Uang Pembayaran Kurang:' : 'Uang Kembalian Pembeli:'"></span>
                            </span>
                            <strong class="text-lg font-extrabold" :class="change < 0 ? 'text-rose-700' : 'text-emerald-700'" x-text="`Rp${money(Math.abs(change))}`"></strong>
                        </div>
                        <p x-show="change >= 0" class="mt-1 text-xs text-emerald-800">
                            Serahkan uang kembalian sebesar <strong x-text="`Rp${money(change)}`"></strong> kepada pembeli.
                        </p>
                        <p x-show="change < 0" class="mt-1 text-xs text-rose-800">
                            Uang yang diterima belum mencukupi total tagihan.
                        </p>
                    </div>
                </div>

                <!-- Petunjuk Pembayaran Non-Tunai -->
                <div x-show="paymentMethod !== 'cash'" class="mt-4">
                    <input type="hidden" name="received_rupiah" :value="money(total)">
                    <div class="rounded-2xl border border-sky-200 bg-sky-50 p-4 text-sky-950">
                        <div class="flex items-center gap-2 text-xs font-bold">
                            <svg class="size-4 shrink-0 text-sky-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Instruksi Pembayaran Non-Tunai</span>
                        </div>
                        <p class="mt-1.5 text-xs leading-relaxed text-sky-900" x-text="paymentMethod === 'qris' ? 'Minta pembeli memindai kode QRIS kasir. Transaksi dicatat tepat sebesar Rp' + money(total) + ' (tanpa kembalian).' : 'Pastikan transfer dana antar bank telah masuk ke rekening toko. Dicatat sebesar Rp' + money(total) + '.'"></p>
                    </div>
                </div>

                <!-- Tombol Submit (Membuka Modal Konfirmasi) -->
                <button 
                    type="button"
                    @click="openConfirmModal()"
                    class="ui-btn ui-btn-primary mt-5 w-full justify-center py-3 text-sm font-bold shadow-sm transition-all" 
                    :disabled="items.length === 0 || (paymentMethod === 'cash' && change < 0)"
                >
                    <svg class="size-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Selesaikan & Simpan Transaksi</span>
                </button>
            </div>
        </aside>
    </form>

    <!-- Modal Konfirmasi Transaksi Penjualan (Reusable & Smooth) -->
    <x-ui.modal :show="'confirmOpen'" title="Konfirmasi Transaksi Penjualan" description="Periksa kembali ringkasan barang belanjaan dan nominal pembayaran sebelum mencetak struk." maxWidth="md">
        <div class="space-y-4">
            <!-- Ringkasan Tagihan -->
            <div class="rounded-2xl bg-cream-50 p-4 border border-zinc-200">
                <div class="flex items-center justify-between text-xs text-zinc-500">
                    <span>Jumlah Macam Barang</span>
                    <span class="font-bold text-zinc-900" x-text="`${items.length} macam (${totalCount} unit)`"></span>
                </div>
                <div class="mt-2.5 flex items-baseline justify-between border-t border-zinc-200 pt-2.5">
                    <span class="text-sm font-bold text-zinc-700">Total Tagihan Belanja</span>
                    <span class="text-2xl font-extrabold text-accent" x-text="`Rp${money(total)}`"></span>
                </div>
            </div>

            <!-- Detail Pembayaran -->
            <div class="rounded-2xl border border-zinc-200 p-4 space-y-2.5 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-zinc-500">Cara Pembayaran</span>
                    <span class="font-bold uppercase tracking-wider text-zinc-900" x-text="paymentMethod === 'cash' ? 'Tunai' : (paymentMethod === 'qris' ? 'QRIS' : 'Transfer Bank')"></span>
                </div>

                <template x-if="paymentMethod === 'cash'">
                    <div class="space-y-2 pt-2 border-t border-dashed border-zinc-200">
                        <div class="flex items-center justify-between">
                            <span class="text-zinc-500">Uang Tunai Diterima</span>
                            <span class="font-bold text-zinc-900 text-sm" x-text="`Rp${money(rawReceived)}`"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-emerald-700 font-bold">Kembalian ke Pembeli</span>
                            <span class="font-extrabold text-emerald-700 text-base" x-text="`Rp${money(Math.max(0, change))}`"></span>
                        </div>
                    </div>
                </template>

                <template x-if="paymentMethod !== 'cash'">
                    <div class="text-xs text-zinc-500 pt-2 border-t border-zinc-100">
                        Pembayaran non-tunai langsung dicatat lunas sesuai total belanja.
                    </div>
                </template>
            </div>

            <p class="text-xs text-zinc-500 text-center">
                Setelah disimpan, struk penjualan akan tercatat resmi dan stok barang otomatis terpotong.
            </p>
        </div>

        <x-slot:footer>
            <button 
                type="button" 
                @click="confirmOpen = false"
                class="ui-btn text-xs font-semibold"
                :disabled="isSubmitting"
            >
                Periksa Lagi
            </button>
            <button 
                type="button" 
                @click="submitForm()"
                class="ui-btn ui-btn-primary text-xs font-bold flex items-center gap-2"
                :disabled="isSubmitting"
            >
                <svg x-show="isSubmitting" class="size-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span x-text="isSubmitting ? 'Menyimpan...' : 'Ya, Selesaikan & Simpan'"></span>
            </button>
        </x-slot:footer>
    </x-ui.modal>

    <!-- Floating Bar di Mobile (< md) saat di Tab Katalog -->
    <div 
        x-show="items.length > 0 && activeTab === 'catalog'"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-4 left-4 right-4 z-40 flex items-center justify-between rounded-2xl bg-zinc-950 p-4 text-white shadow-2xl md:hidden"
    >
        <div class="min-w-0 flex-1">
            <div class="text-xs text-zinc-400" x-text="`${items.length} macam (${totalCount} unit)`"></div>
            <div class="text-base font-extrabold text-accent" x-text="`Rp${money(total)}`"></div>
        </div>

        <button 
            type="button" 
            @click="activeTab = 'cart'"
            class="flex items-center gap-1.5 rounded-xl bg-accent px-4 py-2.5 text-xs font-bold text-white shadow-xs transition-transform active:scale-95"
        >
            <span>Buka Keranjang</span>
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </button>
    </div>

</div>
</x-layouts::app>
