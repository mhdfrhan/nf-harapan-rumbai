<x-layouts::app :title="__('Penyesuaian Stok (Opname)')">
    <div class="ui-page max-w-3xl space-y-6">
        <x-ui.page-header 
            eyebrow="Gudang & Inventaris" 
            title="Formulir Penyesuaian Stok Fisik" 
            description="Gunakan saat stok fisik di toko tidak sesuai dengan catatan sistem karena barang rusak, hilang, atau hasil stock opname."
        />

        @if($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('inventory.adjustments.store') }}" class="ui-panel overflow-hidden">
            @csrf
            <input type="hidden" name="idempotency_key" value="{{ $idempotencyKey }}">

            <div class="ui-panel-body space-y-5">
                <div>
                    <label class="ui-label" for="product_id">Pilih Produk Barang</label>
                    <select id="product_id" name="product_id" class="ui-select" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} — Stok Sistem: {{ rtrim(rtrim(number_format((float)($product->stockBalance?->quantity_on_hand ?? 0), 4, ',', '.'), '0'), ',') }} {{ $product->baseUnit?->code }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-zinc-500">Pilih produk yang ingin disesuaikan jumlah stok fisiknya.</p>
                </div>

                <div>
                    <label class="ui-label" for="physical_quantity">Jumlah Stok Fisik Sebenarnya di Toko</label>
                    <input 
                        id="physical_quantity" 
                        name="physical_quantity" 
                        type="number" 
                        step="0.0001" 
                        min="0" 
                        class="ui-input text-base font-bold text-accent" 
                        placeholder="Contoh: 25" 
                        required
                    >
                    <p class="mt-1.5 text-xs text-zinc-500">Masukkan total fisik riil yang ada di rak toko/gudang setelah dihitung.</p>
                </div>

                <div>
                    <label class="ui-label" for="reason_code">Alasan Penyesuaian Stok</label>
                    <select id="reason_code" name="reason_code" class="ui-select">
                        <option value="physical_count">Hasil Stock Opname (Hitung Fisik Rutin)</option>
                        <option value="damaged">Barang Rusak / Pecah / Cacat</option>
                        <option value="lost">Barang Hilang / Selisih Kasir</option>
                        <option value="expired">Barang Kadaluwarsa</option>
                        <option value="input_correction">Koreksi Salah Input Data Sebelumnya</option>
                        <option value="opening_balance">Saldo Awal Pembukaan Toko</option>
                    </select>
                </div>

                <div>
                    <label class="ui-label" for="notes">Keterangan / Catatan Tambahan (Wajib)</label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        class="ui-textarea" 
                        placeholder="Tuliskan keterangan detail mengapa dilakukan penyesuaian stok ini..." 
                        required
                    ></textarea>
                </div>
            </div>

            <div class="border-t border-zinc-100 bg-cream-50/60 p-5 flex items-center justify-end gap-3">
                <a class="ui-btn font-semibold text-xs" href="{{ route('inventory.index') }}" wire:navigate>
                    Batal
                </a>
                <button class="ui-btn ui-btn-primary font-bold text-xs shadow-sm" type="submit">
                    Simpan Penyesuaian Stok
                </button>
            </div>
        </form>
    </div>
</x-layouts::app>
