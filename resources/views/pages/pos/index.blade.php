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

<x-layouts::app :title="__('Kasir (POS)')">
<div class="ui-page max-w-none pb-20 md:pb-6" x-data="{
    catalog: @js($catalog),
    items: [],
    search: '',
    selectedCategory: '',
    paymentMethod: 'cash',
    received: '',
    activeTab: 'catalog',
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

    <!-- Page Header (Compact on mobile) -->
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-ui.page-header eyebrow="Operasional" title="Kasir (POS)" description="Harga, konversi, stok, dan total dihitung otomatis." />
        </div>

        <!-- Mobile Navigation Tabs (Shown on small screens < md) -->
        <div class="flex items-center rounded-xl bg-zinc-100 p-1 dark:bg-zinc-800 md:hidden">
            <button 
                type="button" 
                @click="activeTab = 'catalog'"
                :class="activeTab === 'catalog' ? 'bg-white text-zinc-900 shadow dark:bg-zinc-700 dark:text-white font-semibold' : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400'"
                class="flex-1 rounded-lg py-2 text-center text-xs transition-all"
            >
                Katalog Produk
            </button>
            <button 
                type="button" 
                @click="activeTab = 'cart'"
                :class="activeTab === 'cart' ? 'bg-white text-zinc-900 shadow dark:bg-zinc-700 dark:text-white font-semibold' : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400'"
                class="relative flex-1 rounded-lg py-2 text-center text-xs transition-all"
            >
                <span>Keranjang</span>
                <template x-if="items.length > 0">
                    <span class="ml-1 inline-flex items-center justify-center rounded-full bg-accent px-1.5 py-0.5 text-[10px] text-white" x-text="items.length"></span>
                </template>
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="ui-panel mb-4 p-4 text-danger">
            <strong>Penjualan belum tersimpan.</strong> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('pos.store') }}" class="grid gap-4 md:grid-cols-[1fr_380px] lg:grid-cols-[1fr_440px]">
        @csrf
        <input type="hidden" name="idempotency_key" value="{{ $idempotencyKey }}">

        <!-- SISI KIRI: Katalog Produk -->
        <section 
            class="ui-panel flex flex-col"
            :class="{ 'hidden md:flex': activeTab !== 'catalog' }"
        >
            <div class="ui-panel-header flex-col items-start gap-3 border-b p-4 sm:flex-row sm:items-center">
                <!-- Search Input with Search Icon -->
                <div class="relative w-full sm:flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-400">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        class="ui-input w-full pl-9" 
                        x-model="search" 
                        placeholder="Cari produk (Nama atau SKU)..." 
                        autofocus
                    >
                </div>

                <!-- Category Filter Pills -->
                @if(isset($categories) && $categories->count() > 0)
                    <div class="flex w-full items-center gap-1.5 overflow-x-auto pb-1 sm:w-auto sm:pb-0">
                        <button 
                            type="button"
                            @click="selectedCategory = ''"
                            :class="selectedCategory === '' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-medium' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-300'"
                            class="whitespace-nowrap rounded-lg px-3 py-1.5 text-xs transition-colors"
                        >
                            Semua
                        </button>
                        @foreach($categories as $cat)
                            <button 
                                type="button"
                                @click="selectedCategory = '{{ $cat->id }}'"
                                :class="selectedCategory === '{{ $cat->id }}' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-medium' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-300'"
                                class="whitespace-nowrap rounded-lg px-3 py-1.5 text-xs transition-colors"
                            >
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Grid Container -->
            <div class="grid gap-3 p-4 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 max-h-[calc(100vh-220px)] overflow-y-auto">
                <template x-for="p in catalog.filter(p => (!search || (`${p.name} ${p.sku}`).toLowerCase().includes(search.toLowerCase())) && (!selectedCategory || p.category_id == selectedCategory))" :key="p.unit_id">
                    <button 
                        type="button" 
                        @click="add(p)" 
                        class="group relative flex flex-col justify-between rounded-xl border border-zinc-200 bg-white p-3 text-left shadow-sm transition-all hover:border-accent hover:shadow-md dark:border-zinc-700 dark:bg-zinc-800"
                    >
                        <!-- Top Info -->
                        <div>
                            <div class="flex items-center justify-between text-[11px] text-zinc-500 dark:text-zinc-400">
                                <span class="truncate font-mono" x-text="p.sku"></span>
                                <span class="rounded bg-zinc-100 px-1.5 py-0.5 font-medium text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300" x-text="p.unit"></span>
                            </div>
                            <strong class="mt-1.5 block text-xs font-semibold text-zinc-900 group-hover:text-accent dark:text-white line-clamp-2" x-text="p.name"></strong>
                        </div>

                        <!-- Bottom Price & Stock -->
                        <div class="mt-3 border-t border-zinc-100 pt-2 dark:border-zinc-700/50">
                            <div class="text-xs font-bold text-accent" x-text="`Rp${money(p.retail)}`"></div>
                            <div class="flex items-center justify-between text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">
                                <span>Stok: <strong x-text="p.stock"></strong></span>
                                <span x-show="p.wholesale" class="text-[10px] text-emerald-600 font-medium dark:text-emerald-400">Ada Grosir</span>
                            </div>
                        </div>

                        <!-- Item Added Indicator Badge -->
                        <template x-if="items.find(i => i.unit_id === p.unit_id)">
                            <span class="absolute -right-1.5 -top-1.5 flex size-6 items-center justify-center rounded-full bg-accent text-[11px] font-bold text-white shadow" x-text="items.find(i => i.unit_id === p.unit_id).qty"></span>
                        </template>
                    </button>
                </template>
            </div>
        </section>

        <!-- SISI KANAN: Keranjang & Pembayaran -->
        <aside 
            class="ui-panel flex flex-col justify-between"
            :class="{ 'hidden md:flex': activeTab !== 'cart' }"
        >
            <div>
                <!-- Cart Header -->
                <div class="ui-panel-header flex items-center justify-between border-b p-4">
                    <div class="flex items-center gap-2">
                        <h2 class="font-semibold text-zinc-900 dark:text-white">Keranjang</h2>
                        <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400" x-text="`${items.length} item`"></span>
                    </div>
                    <button 
                        type="button" 
                        class="text-xs font-medium text-rose-600 hover:underline dark:text-rose-400" 
                        @click="items=[]"
                        x-show="items.length > 0"
                    >
                        Kosongkan
                    </button>
                </div>

                <!-- Cart Items List -->
                <div class="divide-y divide-zinc-100 max-h-[calc(100vh-440px)] overflow-y-auto dark:divide-zinc-800">
                    <template x-if="items.length === 0">
                        <div class="p-8 text-center text-zinc-500 dark:text-zinc-400">
                            <svg class="mx-auto size-10 text-zinc-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                            </svg>
                            <p class="mt-2 text-xs">Keranjang masih kosong.</p>
                            <p class="text-[11px] text-zinc-400">Pilih produk di sebelah untuk menambahkan.</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in items" :key="item.unit_id">
                        <div class="p-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <input type="hidden" :name="`items[${index}][product_unit_id]`" :value="item.unit_id">
                            
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <strong class="block truncate text-xs font-semibold text-zinc-900 dark:text-white" x-text="item.name"></strong>
                                    <p class="text-[11px] text-zinc-500 dark:text-zinc-400" x-text="`${item.unit} · ${item.qty >= item.minimum && item.wholesale ? 'Harga grosir' : 'Harga eceran'}`"></p>
                                </div>
                                <button 
                                    type="button" 
                                    @click="items.splice(index, 1)"
                                    class="text-zinc-400 hover:text-rose-600 dark:hover:text-rose-400"
                                >
                                    &times;
                                </button>
                            </div>

                            <div class="mt-2 flex items-center justify-between">
                                <!-- Qty Increment / Decrement Controls -->
                                <div class="flex items-center rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
                                    <button 
                                        type="button"
                                        @click="updateQty(index, -1)"
                                        class="flex size-7 items-center justify-center text-zinc-500 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-700 rounded-l-lg"
                                    >
                                        -
                                    </button>
                                    <input 
                                        :name="`items[${index}][quantity]`" 
                                        x-model.number="item.qty" 
                                        type="number" 
                                        min="0.0001" 
                                        step="0.0001" 
                                        class="w-14 text-center text-xs font-semibold focus:outline-none bg-transparent"
                                    >
                                    <button 
                                        type="button"
                                        @click="updateQty(index, 1)"
                                        class="flex size-7 items-center justify-center text-zinc-500 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-700 rounded-r-lg"
                                    >
                                        +
                                    </button>
                                </div>

                                <strong class="text-xs font-bold text-zinc-900 dark:text-white" x-text="`Rp${money(subtotal(item))}`"></strong>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Payment & Checkout Panel -->
            <div class="border-t border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-900/50">
                <input type="hidden" name="payment_method" :value="paymentMethod">

                <!-- Total Pembayaran -->
                <div class="flex items-center justify-between text-base font-bold text-zinc-900 dark:text-white">
                    <span>Total Pembayaran</span>
                    <span class="text-accent text-lg" x-text="`Rp${money(total)}`"></span>
                </div>

                <!-- Segmented Payment Method Selector (Card / Pill Buttons with SVG icons) -->
                <div class="mt-3">
                    <label class="ui-label text-[11px] mb-1.5 block">Metode Pembayaran</label>
                    <div class="grid grid-cols-3 gap-1 rounded-xl bg-zinc-200/70 p-1 dark:bg-zinc-800">
                        <button 
                            type="button" 
                            @click="selectPaymentMethod('cash')"
                            :class="paymentMethod === 'cash' ? 'bg-white text-zinc-900 shadow font-bold dark:bg-zinc-700 dark:text-white' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400'"
                            class="flex items-center justify-center gap-1.5 rounded-lg py-2 text-xs transition-all"
                        >
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Tunai</span>
                        </button>
                        <button 
                            type="button" 
                            @click="selectPaymentMethod('qris')"
                            :class="paymentMethod === 'qris' ? 'bg-white text-zinc-900 shadow font-bold dark:bg-zinc-700 dark:text-white' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400'"
                            class="flex items-center justify-center gap-1.5 rounded-lg py-2 text-xs transition-all"
                        >
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <span>QRIS</span>
                        </button>
                        <button 
                            type="button" 
                            @click="selectPaymentMethod('transfer')"
                            :class="paymentMethod === 'transfer' ? 'bg-white text-zinc-900 shadow font-bold dark:bg-zinc-700 dark:text-white' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400'"
                            class="flex items-center justify-center gap-1.5 rounded-lg py-2 text-xs transition-all"
                        >
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                            <span>Transfer</span>
                        </button>
                    </div>
                </div>

                <!-- CASH OPSI NOMINAL PENCAHAN & INPUT -->
                <div x-show="paymentMethod === 'cash'" class="mt-3">
                    <!-- Quick Cash Suggestion Pills -->
                    <div x-show="items.length > 0">
                        <label class="ui-label text-[11px] mb-1.5 block">Opsi Uang Diterima:</label>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="opt in quickCashOptions" :key="opt">
                                <button 
                                    type="button"
                                    @click="setCashAmount(opt)"
                                    :class="rawReceived === opt ? 'bg-zinc-900 text-white font-bold dark:bg-white dark:text-zinc-900 scale-105 shadow' : 'bg-white text-zinc-800 border border-zinc-300 hover:border-accent dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700'"
                                    class="rounded-lg px-2.5 py-1.5 text-xs transition-all shadow-sm active:scale-95"
                                >
                                    <span x-text="opt === total ? 'Uang Pas (Rp' + money(opt) + ')' : 'Rp' + money(opt)"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Manual Cash Input -->
                    <div class="mt-3">
                        <label class="ui-label text-[11px]">Nominal Uang Diterima (Manual)</label>
                        <input name="received_rupiah" x-model="received" inputmode="numeric" class="ui-input py-1.5 text-xs font-semibold" placeholder="Masukkan jumlah uang" required>
                    </div>

                    <!-- Informatif Banner Kembalian / Kekurangan -->
                    <div x-show="items.length > 0 && received" class="mt-3 rounded-xl p-3 border" :class="change < 0 ? 'bg-rose-50 border-rose-200 dark:bg-rose-950/40 dark:border-rose-800' : 'bg-emerald-50 border-emerald-200 dark:bg-emerald-950/40 dark:border-emerald-800'">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium" :class="change < 0 ? 'text-rose-700 dark:text-rose-300' : 'text-emerald-700 dark:text-emerald-300'">
                                <span x-text="change < 0 ? 'Uang Kurang:' : 'Kembalian:'"></span>
                            </span>
                            <strong class="text-sm font-extrabold" :class="change < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400'" x-text="`Rp${money(Math.abs(change))}`"></strong>
                        </div>
                        <p x-show="change >= 0" class="mt-1 text-[11px] text-emerald-700 dark:text-emerald-300">
                            Berikan kembalian <strong x-text="`Rp${money(change)}`"></strong> kepada pelanggan.
                        </p>
                        <p x-show="change < 0" class="mt-1 text-[11px] text-rose-700 dark:text-rose-300">
                            Nominal uang diterima masih kurang sebesar <strong x-text="`Rp${money(Math.abs(change))}`"></strong>.
                        </p>
                    </div>
                </div>

                <!-- NON-CASH (QRIS & TRANSFER) INFO BOX -->
                <div x-show="paymentMethod !== 'cash'" class="mt-3">
                    <input type="hidden" name="received_rupiah" :value="money(total)">
                    <div class="rounded-xl border border-sky-200 bg-sky-50 p-3 text-sky-900 dark:border-sky-800 dark:bg-sky-950/40 dark:text-sky-200">
                        <div class="flex items-center gap-1.5 text-xs font-bold">
                            <svg class="size-4 shrink-0 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Petunjuk Pembayaran</span>
                        </div>
                        <p class="mt-1 text-[11px] leading-relaxed opacity-90" x-text="paymentMethod === 'qris' ? 'Minta pelanggan memindai kode QRIS. Pembayaran akan dicatat sebesar Rp' + money(total) + ' (tanpa kembalian).' : 'Pastikan transaksi transfer bank sudah berhasil diterima. Pembayaran dicatat sebesar Rp' + money(total) + ' (tanpa kembalian).'"></p>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="ui-btn ui-btn-primary mt-4 w-full justify-center py-2.5 text-xs font-semibold" 
                    :disabled="items.length === 0 || (paymentMethod === 'cash' && change < 0)"
                >
                    Selesaikan Penjualan
                </button>
            </div>
        </aside>
    </form>

    <!-- FLOATING BOTTOM BAR FOR MOBILE PORTRAIT (< md) -->
    <div 
        x-show="items.length > 0 && activeTab === 'catalog'"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-4 left-4 right-4 z-40 flex items-center justify-between rounded-2xl bg-zinc-900 p-3 text-white shadow-xl dark:bg-zinc-800 md:hidden"
    >
        <div class="min-w-0 flex-1">
            <div class="text-[11px] text-zinc-400" x-text="`${items.length} jenis item (${totalCount} unit)`"></div>
            <div class="text-sm font-bold text-accent" x-text="`Rp${money(total)}`"></div>
        </div>

        <button 
            type="button" 
            @click="activeTab = 'cart'"
            class="flex items-center gap-1 rounded-xl bg-accent px-4 py-2 text-xs font-bold text-white shadow transition-transform active:scale-95"
        >
            <span>Lihat Keranjang</span>
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </button>
    </div>

</div>
</x-layouts::app>
