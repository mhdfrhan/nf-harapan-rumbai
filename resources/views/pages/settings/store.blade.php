<x-layouts::app :title="__('Pengaturan toko')">
    @php
        $values = array_merge([
            'name' => config('store.name'), 'address' => config('store.address'), 'phone' => '', 'owner' => 'Ibu Emi Erawati',
            'open_days' => 'Senin sampai Sabtu', 'open_hours' => '09.00 sampai 22.00',
            'receipt_footer' => 'Terima kasih telah berbelanja di Toko NF Harapan Rumbai.',
        ], $profile);
    @endphp
    <div class="ui-page max-w-5xl">
        <x-ui.page-header eyebrow="Administrasi" title="Pengaturan toko" description="Informasi yang dipakai pada identitas sistem, laporan, dan struk." />
        <form method="POST" action="{{ route('settings.store.update') }}" class="space-y-5">@csrf @method('PUT')
            @if(!auth()->user()->hasPermission('settings.manage'))<fieldset disabled class="contents">@endif
            <section class="ui-panel"><div class="ui-panel-header"><div><h2 class="font-semibold text-zinc-900">Profil toko</h2><p class="mt-1 text-sm text-zinc-600">Gunakan data resmi yang dikenali pemasok dan pelanggan.</p></div></div><div class="ui-panel-body grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2"><label class="ui-label" for="store-name">Nama toko</label><input id="store-name" name="name" class="ui-input" value="{{ old('name', $values['name']) }}" required maxlength="180"></div>
                <div class="sm:col-span-2"><label class="ui-label" for="store-address">Alamat lengkap</label><textarea id="store-address" name="address" class="ui-textarea" required maxlength="1000">{{ old('address', $values['address']) }}</textarea></div>
                <div><label class="ui-label" for="store-phone">Nomor telepon</label><input id="store-phone" name="phone" class="ui-input" value="{{ old('phone', $values['phone']) }}" maxlength="40"></div>
                <div><label class="ui-label" for="store-owner">Penanggung jawab</label><input id="store-owner" name="owner" class="ui-input" value="{{ old('owner', $values['owner']) }}" required maxlength="120"></div>
            </div></section>
            <section class="ui-panel"><div class="ui-panel-header"><div><h2 class="font-semibold text-zinc-900">Operasional dan struk</h2><p class="mt-1 text-sm text-zinc-600">Atur jadwal toko dan pesan penutup struk.</p></div></div><div class="ui-panel-body grid gap-5 sm:grid-cols-2">
                <div><label class="ui-label" for="open-days">Hari operasional</label><input id="open-days" name="open_days" class="ui-input" value="{{ old('open_days', $values['open_days']) }}" required maxlength="120"></div>
                <div><label class="ui-label" for="open-hours">Jam operasional</label><input id="open-hours" name="open_hours" class="ui-input" value="{{ old('open_hours', $values['open_hours']) }}" required maxlength="120"></div>
                <div class="sm:col-span-2"><label class="ui-label" for="receipt-footer">Pesan pada struk</label><textarea id="receipt-footer" name="receipt_footer" class="ui-textarea" maxlength="500">{{ old('receipt_footer', $values['receipt_footer']) }}</textarea></div>
            </div></section>
            <section class="ui-panel p-5"><h2 class="font-semibold text-zinc-900">Aturan keamanan transaksi</h2><p class="mt-2 text-sm leading-6 text-zinc-600">Stok negatif dinonaktifkan dan bukti pembayaran kewajiban diwajibkan oleh konfigurasi server. Aturan ini sengaja tidak dapat dilemahkan dari antarmuka.</p></section>
            @if ($errors->any())<div class="text-sm text-red-700">{{ $errors->first() }}</div>@endif
            <div class="flex justify-end border-t border-zinc-300 pt-5">@if(auth()->user()->hasPermission('settings.manage'))<button type="submit" class="ui-btn ui-btn-primary">Simpan pengaturan</button>@else<span class="text-sm text-zinc-600">Mode baca saja</span>@endif</div>
            @if(!auth()->user()->hasPermission('settings.manage'))</fieldset>@endif
        </form>
    </div>
</x-layouts::app>
