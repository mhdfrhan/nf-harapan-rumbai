<x-layouts::app :title="__('Pengaturan Toko')">
    @php
        $values = array_merge([
            'name' => config('store.name'), 
            'address' => config('store.address'), 
            'phone' => '', 
            'owner' => 'Ibu Emi Erawati',
            'open_days' => 'Senin sampai Sabtu', 
            'open_hours' => '09.00 sampai 22.00',
            'receipt_footer' => 'Terima kasih telah berbelanja di Toko NF Harapan Rumbai.',
        ], $profile);
    @endphp

    <div class="ui-page max-w-5xl space-y-6">
        <x-ui.page-header 
            eyebrow="Administrasi Toko" 
            title="Pengaturan Profil Toko" 
            description="Informasi resmi toko yang tercetak pada kop faktur, struk kasir penjualan, dan laporan operasional." 
        />

        @if(session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('settings.store.update') }}" class="space-y-6">
            @csrf 
            @method('PUT')

            @if(!auth()->user()->hasPermission('settings.manage'))
                <fieldset disabled class="contents">
            @endif

            <!-- 1. Identitas & Kontak Toko -->
            <section class="ui-panel overflow-hidden">
                <div class="ui-panel-header">
                    <div>
                        <h2 class="text-base font-bold text-zinc-950">1. Profil & Kontak Resmi Toko</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Data identitas toko yang dikenali oleh pelanggan dan distributor.</p>
                    </div>
                </div>

                <div class="ui-panel-body grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="ui-label" for="store-name">Nama Toko</label>
                        <input id="store-name" name="name" class="ui-input text-base font-bold" value="{{ old('name', $values['name']) }}" required maxlength="180">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="ui-label" for="store-address">Alamat Lengkap Toko</label>
                        <textarea id="store-address" name="address" class="ui-textarea text-sm" required maxlength="1000">{{ old('address', $values['address']) }}</textarea>
                    </div>

                    <div>
                        <label class="ui-label" for="store-phone">Nomor Telepon / WhatsApp Toko</label>
                        <input id="store-phone" name="phone" class="ui-input font-medium" value="{{ old('phone', $values['phone']) }}" maxlength="40">
                    </div>

                    <div>
                        <label class="ui-label" for="store-owner">Pemilik / Penanggung Jawab</label>
                        <input id="store-owner" name="owner" class="ui-input font-medium" value="{{ old('owner', $values['owner']) }}" required maxlength="120">
                    </div>
                </div>
            </section>

            <!-- 2. Jam Buka & Catatan Struk Kasir -->
            <section class="ui-panel overflow-hidden">
                <div class="ui-panel-header">
                    <div>
                        <h2 class="text-base font-bold text-zinc-950">2. Waktu Operasional & Pesan Penutup Struk</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Jadwal buka toko dan kalimat salam penutup pada struk kasir.</p>
                    </div>
                </div>

                <div class="ui-panel-body grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="ui-label" for="open-days">Hari Buka Operasional</label>
                        <input id="open-days" name="open_days" class="ui-input font-medium" value="{{ old('open_days', $values['open_days']) }}" placeholder="Contoh: Senin - Sabtu" required maxlength="120">
                    </div>

                    <div>
                        <label class="ui-label" for="open-hours">Jam Buka Operasional</label>
                        <input id="open-hours" name="open_hours" class="ui-input font-medium" value="{{ old('open_hours', $values['open_hours']) }}" placeholder="Contoh: 08.00 - 22.00 WIB" required maxlength="120">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="ui-label" for="receipt-footer">Pesan Ucapan di Bawah Struk Kasir</label>
                        <textarea id="receipt-footer" name="receipt_footer" class="ui-textarea" placeholder="Contoh: Terima kasih telah berbelanja..." maxlength="500">{{ old('receipt_footer', $values['receipt_footer']) }}</textarea>
                        <p class="mt-1.5 text-xs text-zinc-500">Teks ini akan otomatis tercetak di baris paling bawah setiap struk belanja kasir.</p>
                    </div>
                </div>
            </section>

            <!-- 3. Aturan Keamanan & Integritas Data -->
            <section class="ui-panel p-5 bg-cream-50/50">
                <div class="flex items-center gap-2 text-zinc-900 font-bold text-sm mb-1">
                    <svg class="size-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Aturan Keamanan Transaksi & Stok Gudang</span>
                </div>
                <p class="text-xs leading-relaxed text-zinc-600">
                    Stok negatif dinonaktifkan secara ketat pada mesin kasir dan bukti pembayaran hutang diwajibkan oleh konfigurasi database. Aturan ini dikunci untuk mencegah manipulasi data persediaan dan menjamin keakuratan laporan keuangan toko.
                </p>
            </section>

            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-medium text-rose-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="flex items-center justify-end gap-3 pt-2">
                @if(auth()->user()->hasPermission('settings.manage'))
                    <button type="submit" class="ui-btn ui-btn-primary font-bold text-xs shadow-sm">
                        <flux:icon.check class="size-4" />
                        <span>Simpan Pengaturan Toko</span>
                    </button>
                @else
                    <span class="text-xs text-zinc-500 font-medium">Akun Anda dalam mode baca saja (hanya pemilik yang dapat mengubah).</span>
                @endif
            </div>

            @if(!auth()->user()->hasPermission('settings.manage'))
                </fieldset>
            @endif
        </form>
    </div>
</x-layouts::app>
