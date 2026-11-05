@php($unitOptions = $products->flatMap(fn($p) => $p->saleUnits->map(fn($u) => ['id' => $u->id, 'label' => $p->name . ' — ' . $u->label]))->values())

<x-layouts::app :title="__('Catat Penerimaan Barang Masuk')">
    <div class="ui-page max-w-6xl space-y-6" x-data="{ rows: [{}], add() { this.rows.push({}) } }">
        <x-ui.page-header 
            eyebrow="Gudang & Pemasok" 
            title="Catat Penerimaan Barang Masuk" 
            description="Masukkan rincian surat jalan atau faktur pembelian barang dari pemasok untuk menambah saldo stok fisik toko."
        />

        @if($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-medium text-rose-800">
                <strong>Mohon periksa kembali isian formulir:</strong> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" enctype="multipart/form-data" action="{{ route('receipts.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="idempotency_key" value="{{ $idempotencyKey }}">

            <!-- 1. Informasi Dokumen & Pemasok -->
            <section class="ui-panel overflow-hidden">
                <div class="ui-panel-header">
                    <div>
                        <h2 class="text-base font-bold text-zinc-950">1. Data Pemasok & Faktur Bon</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Identitas toko rekanan dan tanggal barang tiba di gudang.</p>
                    </div>
                </div>

                <div class="ui-panel-body grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="ui-label" for="supplier_id">Pilih Pemasok / Distributor</label>
                        <select id="supplier_id" name="supplier_id" class="ui-select" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="ui-label" for="received_on">Tanggal Barang Diterima</label>
                        <input id="received_on" name="received_on" type="date" value="{{ old('received_on', today()->toDateString()) }}" class="ui-input font-medium" required>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="ui-label mb-0" for="supplier_document_number">Nomor Nota Pemasok</label>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 border border-emerald-200">
                                Terisi otomatis
                            </span>
                        </div>
                        <input 
                            id="supplier_document_number" 
                            name="supplier_document_number" 
                            class="ui-input font-mono text-zinc-800" 
                            value="{{ old('supplier_document_number', $defaultDocumentNumber ?? '') }}" 
                            placeholder="Contoh: NOTA-20261102-0001"
                        >
                        <p class="mt-1.5 text-xs text-zinc-500">
                            Bila ada nomor faktur cetak di kertas bon dari pemasok, Anda dapat menggantinya sesuai nomor bon tersebut.
                        </p>
                    </div>

                    <div>
                        <label class="ui-label" for="notes">Catatan Tambahan (Opsional)</label>
                        <input id="notes" name="notes" class="ui-input" placeholder="Contoh: Kiriman tahap 1, kondisi kardus baik">
                    </div>
                </div>
            </section>

            <!-- 2. Rincian Barang yang Masuk -->
            <section class="ui-panel overflow-hidden">
                <div class="ui-panel-header">
                    <div>
                        <h2 class="text-base font-bold text-zinc-950">2. Rincian Barang Dagangan Diterima</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Pilih produk, masukkan jumlah barang, dan harga modal beli per satuan.</p>
                    </div>
                    <button type="button" class="ui-btn ui-btn-primary text-xs font-bold" @click="add()">
                        <flux:icon.plus class="size-4" />
                        <span>+ Tambah Baris Barang</span>
                    </button>
                </div>

                <div class="ui-panel-body space-y-3">
                    <template x-for="(row, index) in rows" :key="index">
                        <div class="grid gap-3 sm:grid-cols-[1fr_180px_200px_44px] items-end rounded-xl border border-zinc-200 bg-cream-50/40 p-3 sm:p-4">
                            <div>
                                <label class="ui-label text-xs">Pilih Produk & Kemasan</label>
                                <select :name="`items[${index}][product_unit_id]`" class="ui-select" required>
                                    @foreach($unitOptions as $option)
                                        <option value="{{ $option['id'] }}">{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="ui-label text-xs">Jumlah Diterima</label>
                                <input :name="`items[${index}][quantity]`" type="number" step="0.0001" min="0.0001" class="ui-input font-bold" placeholder="Jumlah" required>
                            </div>

                            <div>
                                <label class="ui-label text-xs">Harga Modal Beli Satuan (Rp)</label>
                                <input :name="`items[${index}][unit_cost_rupiah]`" class="ui-input font-bold text-accent" inputmode="numeric" placeholder="Contoh: 12000" required>
                            </div>

                            <div>
                                <button 
                                    type="button" 
                                    @click="rows.length > 1 ? rows.splice(index, 1) : null" 
                                    class="ui-icon-btn w-full hover:border-rose-400 hover:text-rose-700" 
                                    title="Hapus baris barang"
                                >
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <!-- 3. Pembayaran & Hutang Pemasok -->
            <section class="ui-panel overflow-hidden">
                <div class="ui-panel-header">
                    <div>
                        <h2 class="text-base font-bold text-zinc-950">3. Pembayaran & Tanggal Jatuh Tempo</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Atur apakah belanja ini dibayar tunai langsung atau dicatat sebagai hutang tempo.</p>
                    </div>
                </div>

                <div class="ui-panel-body grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="ui-label" for="initial_payment_rupiah">Uang Pembayaran Awal / DP (Rp)</label>
                        <input id="initial_payment_rupiah" name="initial_payment_rupiah" value="0" class="ui-input text-base font-bold text-zinc-950" inputmode="numeric">
                        <p class="mt-1.5 text-xs text-zinc-500">Isi 0 jika pembayaran tempo penuh (hutang). Isi lunas jika langsung dibayar.</p>
                    </div>

                    <div>
                        <label class="ui-label" for="payment_method">Cara Pembayaran</label>
                        <select id="payment_method" name="payment_method" class="ui-select">
                            <option value="cash">Tunai (Kas Toko)</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>

                    <div>
                        <label class="ui-label" for="due_on">Tanggal Jatuh Tempo (Jika Belum Lunas)</label>
                        <input id="due_on" name="due_on" type="date" class="ui-input">
                        <p class="mt-1.5 text-xs text-zinc-500">Batas akhir pembayaran sisa tagihan kepada pihak pemasok.</p>
                    </div>

                    <div>
                        <label class="ui-label" for="proof">Unggah Foto Nota / Surat Jalan Fisik (Opsional)</label>
                        <input id="proof" name="proof" type="file" accept="image/jpeg,image/png,application/pdf" class="ui-input pt-2 text-xs">
                        <p class="mt-1.5 text-xs text-zinc-500">Format yang diterima: JPG, PNG, atau PDF.</p>
                    </div>
                </div>
            </section>

            <!-- Aksi Tombol -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a class="ui-btn font-semibold text-xs" href="{{ route('receipts.index') }}" wire:navigate>
                    Batal
                </a>
                <button class="ui-btn ui-btn-primary font-bold text-xs shadow-sm" type="submit">
                    <flux:icon.check class="size-4" />
                    <span>Posting Penerimaan Barang</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts::app>
