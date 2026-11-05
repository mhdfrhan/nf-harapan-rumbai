@php($editing = (bool)$product)
@php($saleUnit = $product?->saleUnits?->firstWhere('is_default_sale_unit', true) ?? $product?->saleUnits?->first())
@php($retail = $saleUnit?->prices?->firstWhere('price_type', \App\Domain\Catalog\Enums\PriceType::Retail))
@php($wholesale = $saleUnit?->prices?->firstWhere('price_type', \App\Domain\Catalog\Enums\PriceType::Wholesale))

<x-layouts::app :title="$editing ? 'Ubah Produk ' . $product->name : 'Tambah Produk Baru'">
    <div class="ui-page max-w-5xl space-y-6">
        <x-ui.page-header 
            eyebrow="Katalog Barang" 
            :title="$editing ? 'Ubah Data Produk: ' . $product->name : 'Tambah Produk Dagangan Baru'" 
            description="Lengkapi identitas produk, kode SKU, satuan jual, dan harga eceran maupun grosir." 
        />

        @if($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                <strong class="font-bold">Mohon periksa kembali isian formulir:</strong>
                <ul class="mt-1 list-disc pl-5 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $editing ? route('catalog.products.update', $product) : route('catalog.products.store') }}" class="space-y-6">
            @csrf
            @if($editing)
                @method('PUT')
                <input type="hidden" name="lock_version" value="{{ $product->lock_version }}">
            @endif

            <!-- 1. Identitas Produk -->
            <section class="ui-panel overflow-hidden">
                <div class="ui-panel-header">
                    <div>
                        <h2 class="text-base font-bold text-zinc-950">1. Identitas & Informasi Produk</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Nama, kategori, dan identifikasi produk yang memudahkan pencarian di kasir.</p>
                    </div>
                </div>

                <div class="ui-panel-body grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="ui-label" for="name">Nama Lengkap Produk Barang</label>
                        <input id="name" name="name" class="ui-input text-base font-medium" placeholder="Contoh: Beras Ramos Super 5kg" required value="{{ old('name', $product?->name) }}">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="ui-label mb-0" for="sku">Kode SKU Produk</label>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                                Otomatis & Terkunci
                            </span>
                        </div>
                        <input 
                            id="sku" 
                            name="sku" 
                            class="ui-input bg-zinc-100/80 font-mono text-zinc-700 cursor-not-allowed border-zinc-200" 
                            required 
                            readonly 
                            value="{{ old('sku', $product?->sku ?? $generatedSku ?? '') }}"
                        >
                        <p class="mt-1.5 text-xs text-zinc-500">
                            Kode SKU dibuat berurutan oleh sistem untuk konsistensi kartu stok gudang.
                        </p>
                    </div>

                    <div>
                        <label class="ui-label" for="barcode">Kode Barcode (Opsional)</label>
                        <input id="barcode" name="barcode" class="ui-input" placeholder="Scan atau ketik kode barcode kemasan" value="{{ old('barcode', $product?->barcode) }}">
                        <p class="mt-1.5 text-xs text-zinc-500">Dapat dipindai langsung menggunakan barcode scanner di kasir.</p>
                    </div>

                    <div>
                        <label class="ui-label" for="category_id">Kategori Produk</label>
                        <select id="category_id" name="category_id" class="ui-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="ui-label" for="is_active">Status Ketersediaan</label>
                        <select id="is_active" name="is_active" class="ui-select">
                            <option value="1" @selected(old('is_active', $product?->is_active ?? true))>Aktif (Bisa Dijual di Kasir)</option>
                            <option value="0" @selected(!old('is_active', $product?->is_active ?? true))>Nonaktif (Disembunyikan)</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="ui-label" for="description">Deskripsi Singkat / Catatan (Opsional)</label>
                        <textarea id="description" name="description" class="ui-textarea" placeholder="Tambahkan catatan khusus, merk, atau rasa...">{{ old('description', $product?->description) }}</textarea>
                    </div>
                </div>
            </section>

            <!-- 2. Satuan & Aturan Harga -->
            <section class="ui-panel overflow-hidden">
                <div class="ui-panel-header">
                    <div>
                        <h2 class="text-base font-bold text-zinc-950">2. Satuan Dasar, Stok & Harga Jual</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Tentukan satuan dasar inventaris serta harga jual eceran dan grosir.</p>
                    </div>
                </div>

                <div class="ui-panel-body grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="ui-label" for="base_unit_id">Satuan Dasar Inventaris</label>
                        <select id="base_unit_id" name="base_unit_id" class="ui-select">
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" @selected(old('base_unit_id', $product?->base_unit_id) == $unit->id)>
                                    {{ $unit->name }} ({{ $unit->code }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1.5 text-xs text-zinc-500">Satuan terkecil untuk menghitung saldo stok di gudang.</p>
                    </div>

                    <div>
                        <label class="ui-label" for="unit_label">Nama Satuan Penjualan Utama</label>
                        <input id="unit_label" name="unit_label" class="ui-input" required placeholder="Contoh: pcs, bks, btl" value="{{ old('unit_label', $saleUnit?->label ?? 'pcs') }}">
                        <p class="mt-1.5 text-xs text-zinc-500">Satuan default yang muncul di layar kasir.</p>
                    </div>

                    <div>
                        <label class="ui-label" for="conversion_to_base">Faktor Konversi ke Satuan Dasar</label>
                        <input id="conversion_to_base" name="conversion_to_base" class="ui-input" type="number" step="0.0001" min="0.0001" value="{{ old('conversion_to_base', $saleUnit?->conversion_to_base ?? 1) }}">
                        <p class="mt-1.5 text-xs text-zinc-500">Isi 1 jika satuan jual sama dengan satuan dasar.</p>
                    </div>

                    <div>
                        <label class="ui-label" for="low_stock_threshold">Batas Peringatan Stok Menipis</label>
                        <input id="low_stock_threshold" name="low_stock_threshold" class="ui-input" type="number" step="0.0001" min="0" value="{{ old('low_stock_threshold', $product?->low_stock_threshold ?? 5) }}">
                        <p class="mt-1.5 text-xs text-zinc-500">Sistem akan memberi tahu jika stok berada di bawah jumlah ini.</p>
                    </div>

                    <div class="sm:col-span-2 pt-2 border-t border-zinc-100">
                        <h3 class="text-sm font-bold text-zinc-900 mb-3">Penetapan Harga Jual</h3>
                    </div>

                    <div>
                        <label class="ui-label" for="retail_price">Harga Jual Eceran (Rp)</label>
                        <input id="retail_price" name="retail_price" class="ui-input text-base font-bold text-accent" required inputmode="numeric" placeholder="Contoh: 15000" value="{{ old('retail_price', $retail?->amount_rupiah) }}">
                        <p class="mt-1.5 text-xs text-zinc-500">Harga standar untuk pembeli umum / satuan.</p>
                    </div>

                    <div>
                        <label class="ui-label" for="wholesale_price">Harga Jual Grosir (Rp, Opsional)</label>
                        <input id="wholesale_price" name="wholesale_price" class="ui-input" inputmode="numeric" placeholder="Contoh: 13500" value="{{ old('wholesale_price', $wholesale?->amount_rupiah) }}">
                        <p class="mt-1.5 text-xs text-zinc-500">Harga potongan khusus untuk pembelian dalam jumlah banyak.</p>
                    </div>

                    <div>
                        <label class="ui-label" for="wholesale_minimum">Minimal Pembelian untuk Harga Grosir</label>
                        <input id="wholesale_minimum" name="wholesale_minimum" class="ui-input" type="number" step="0.0001" min="0.0001" value="{{ old('wholesale_minimum', $wholesale?->minimum_quantity ?? 10) }}">
                        <p class="mt-1.5 text-xs text-zinc-500">Jumlah belanja minimal agar harga grosir otomatis aktif di kasir.</p>
                    </div>
                </div>
            </section>

            <!-- Tombol Aksi Simpan & Batal -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a class="ui-btn font-semibold" href="{{ route('catalog.products.index') }}" wire:navigate>
                    Batal
                </a>
                <button class="ui-btn ui-btn-primary font-bold shadow-sm" type="submit">
                    <flux:icon.check class="size-4" />
                    <span>{{ $editing ? 'Simpan Perubahan Produk' : 'Daftarkan Produk Baru' }}</span>
                </button>
            </div>
        </form>

        <!-- Satuan Jual Tambahan (Hanya Tampil Saat Edit) -->
        @if($editing && auth()->user()->hasPermission('product_units.manage'))
            <section class="ui-panel overflow-hidden">
                <div class="ui-panel-header">
                    <div>
                        <h2 class="text-base font-bold text-zinc-950">3. Satuan Kemasan Tambahan (Opsional)</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Gunakan jika produk ini juga dijual per dus, karton, renteng, atau pack.</p>
                    </div>
                </div>

                <div class="ui-panel-body space-y-4">
                    @foreach($product->saleUnits->where('is_default_sale_unit', false) as $extra)
                        @php($extraRetail = $extra->prices->firstWhere('price_type', \App\Domain\Catalog\Enums\PriceType::Retail))
                        @php($extraWholesale = $extra->prices->firstWhere('price_type', \App\Domain\Catalog\Enums\PriceType::Wholesale))
                        <form method="POST" action="{{ route('catalog.products.units.update', [$product, $extra]) }}" class="grid gap-3 rounded-xl border border-zinc-200 bg-cream-50/50 p-4 sm:grid-cols-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="ui-label text-xs">Satuan</label>
                                <select name="unit_id" class="ui-select text-xs" required>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" @selected($extra->unit_id === $unit->id)>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="ui-label text-xs">Label Satuan</label>
                                <input name="label" class="ui-input text-xs" value="{{ $extra->label }}" required>
                            </div>
                            <div>
                                <label class="ui-label text-xs">Barcode Kemasan</label>
                                <input name="barcode" class="ui-input text-xs" value="{{ $extra->barcode }}" placeholder="Barcode opsional">
                            </div>
                            <div>
                                <label class="ui-label text-xs">Faktor Konversi</label>
                                <input name="conversion_to_base" type="number" step="0.0001" min="0.0001" class="ui-input text-xs" value="{{ $extra->conversion_to_base }}" required>
                            </div>
                            <div>
                                <label class="ui-label text-xs">Harga Eceran (Rp)</label>
                                <input name="retail_price" class="ui-input text-xs font-bold text-accent" value="{{ $extraRetail?->amount_rupiah }}" required>
                            </div>
                            <div>
                                <label class="ui-label text-xs">Harga Grosir (Rp)</label>
                                <input name="wholesale_price" class="ui-input text-xs" value="{{ $extraWholesale?->amount_rupiah }}" placeholder="Grosir opsional">
                            </div>
                            <div>
                                <label class="ui-label text-xs">Minimal Grosir</label>
                                <input name="wholesale_minimum" type="number" step="0.0001" min="0.0001" class="ui-input text-xs" value="{{ $extraWholesale?->minimum_quantity ?? 1 }}">
                            </div>
                            <div>
                                <label class="ui-label text-xs">Status</label>
                                <select name="is_active" class="ui-select text-xs">
                                    <option value="1" @selected($extra->is_active)>Aktif</option>
                                    <option value="0" @selected(!$extra->is_active)>Nonaktif</option>
                                </select>
                            </div>
                            <div class="sm:self-end">
                                <button class="ui-btn text-xs font-semibold w-full" type="submit">Simpan Satuan</button>
                            </div>
                        </form>
                    @endforeach

                    <!-- Form Tambah Kemasan Baru -->
                    <form method="POST" action="{{ route('catalog.products.units.store', $product) }}" class="grid gap-3 rounded-xl border border-dashed border-zinc-300 bg-white p-4 sm:grid-cols-3">
                        @csrf
                        <input type="hidden" name="is_active" value="1">
                        <div>
                            <label class="ui-label text-xs">Pilih Satuan</label>
                            <select name="unit_id" class="ui-select text-xs" required>
                                <option value="">Pilih Satuan</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="ui-label text-xs">Nama Label</label>
                            <input name="label" class="ui-input text-xs" placeholder="Contoh: Karton / Dus" required>
                        </div>
                        <div>
                            <label class="ui-label text-xs">Barcode</label>
                            <input name="barcode" class="ui-input text-xs" placeholder="Barcode kemasan (opsional)">
                        </div>
                        <div>
                            <label class="ui-label text-xs">Konversi Satuan Dasar</label>
                            <input name="conversion_to_base" type="number" step="0.0001" min="0.0001" class="ui-input text-xs" placeholder="Misal: 24 (1 dus isi 24 pcs)" required>
                        </div>
                        <div>
                            <label class="ui-label text-xs">Harga Jual Eceran</label>
                            <input name="retail_price" class="ui-input text-xs font-bold text-accent" placeholder="Harga eceran kemasan" required>
                        </div>
                        <div>
                            <label class="ui-label text-xs">Harga Jual Grosir</label>
                            <input name="wholesale_price" class="ui-input text-xs" placeholder="Harga grosir kemasan">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="ui-label text-xs">Minimal Grosir Kemasan</label>
                            <input name="wholesale_minimum" type="number" step="0.0001" min="0.0001" class="ui-input text-xs" value="1">
                        </div>
                        <div class="sm:self-end">
                            <button class="ui-btn ui-btn-primary text-xs font-bold w-full" type="submit">
                                + Tambah Kemasan Jual
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        @endif
    </div>
</x-layouts::app>
