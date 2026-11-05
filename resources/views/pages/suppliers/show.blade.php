<x-layouts::app :title="$supplier->name">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Rincian Rekanan" 
            :title="$supplier->name" 
            :description="$supplier->notes ?: 'Informasi kontak distributor dan daftar riwayat faktur penerimaan barang dagangan.'"
        >
            <x-slot:actions>
                @if (auth()->user()->hasPermission('purchases.create'))
                    <a class="ui-btn ui-btn-primary font-bold text-xs" href="{{ route('receipts.create') }}" wire:navigate>
                        <flux:icon.plus class="size-4" />
                        <span>Catat Barang Masuk</span>
                    </a>
                @endif
                <a href="{{ route('suppliers.index') }}" wire:navigate class="ui-btn text-xs font-semibold">
                    <span>Kembali ke Daftar</span>
                </a>
            </x-slot:actions>
        </x-ui.page-header>

        @if(session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <!-- Formulir Ubah Data Pemasok (Collapsible) -->
        @if(auth()->user()->hasPermission('suppliers.manage'))
            <details class="ui-panel overflow-hidden group">
                <summary class="ui-panel-header cursor-pointer select-none">
                    <span class="text-sm font-bold text-zinc-950">Ubah Data Kontak & Keterangan Pemasok</span>
                    <span class="text-xs font-semibold text-accent group-open:rotate-180 transition-transform">
                        Buka Edit
                    </span>
                </summary>

                <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="p-5 space-y-4 border-t border-zinc-100 bg-cream-50/40">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="ui-label" for="edit_name">Nama Pemasok</label>
                            <input id="edit_name" name="name" class="ui-input" value="{{ $supplier->name }}" required>
                        </div>
                        <div>
                            <label class="ui-label" for="edit_phone">Nomor Telepon</label>
                            <input id="edit_phone" name="phone" class="ui-input" value="{{ $supplier->phone }}">
                        </div>
                        <div>
                            <label class="ui-label" for="edit_address">Alamat Gudang</label>
                            <textarea id="edit_address" name="address" class="ui-textarea">{{ $supplier->address }}</textarea>
                        </div>
                        <div>
                            <label class="ui-label" for="edit_notes">Catatan Khusus</label>
                            <textarea id="edit_notes" name="notes" class="ui-textarea">{{ $supplier->notes }}</textarea>
                        </div>
                        <div>
                            <label class="ui-label" for="edit_is_active">Status Pemasok</label>
                            <select id="edit_is_active" name="is_active" class="ui-select">
                                <option value="1" @selected($supplier->is_active)>Aktif (Dapat Terima Barang)</option>
                                <option value="0" @selected(!$supplier->is_active)>Nonaktif (Diarsipkan)</option>
                            </select>
                        </div>
                        <div class="self-end">
                            <button class="ui-btn ui-btn-primary font-bold text-xs w-full" type="submit">
                                Simpan Perubahan Pemasok
                            </button>
                        </div>
                    </div>
                </form>
            </details>
        @endif

        <div class="grid gap-6 lg:grid-cols-[340px_minmax(0,1fr)]">
            <!-- Sidebar Informasi Kontak & Hutang -->
            <aside class="space-y-4">
                <section class="ui-panel p-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-accent text-sm font-extrabold text-white shadow-xs">
                            {{ str($supplier->name)->explode(' ')->map(fn ($word) => str($word)->substr(0, 1))->take(2)->join('') }}
                        </div>
                        <div>
                            <h2 class="font-bold text-zinc-950 text-base">{{ $supplier->name }}</h2>
                            <x-ui.status :value="$supplier->is_active ? 'Aktif' : 'Nonaktif'" />
                        </div>
                    </div>

                    <dl class="space-y-3 border-t border-zinc-100 pt-3 text-sm">
                        <div>
                            <dt class="text-xs font-semibold text-zinc-500 uppercase">Nomor Telepon / WA</dt>
                            <dd class="mt-0.5 font-bold text-zinc-900">{{ $supplier->phone ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-zinc-500 uppercase">Alamat Pengiriman</dt>
                            <dd class="mt-0.5 text-zinc-700 leading-relaxed">{{ $supplier->address ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-zinc-500 uppercase">Catatan</dt>
                            <dd class="mt-0.5 text-zinc-700 leading-relaxed">{{ $supplier->notes ?: '-' }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="ui-panel p-5">
                    <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider block">Total Sisa Hutang Toko</span>
                    <p class="ui-kpi-value text-accent">Rp{{ number_format($supplier->payables->sum('balance_rupiah'), 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-zinc-500">Kewajiban pembayaran yang belum terselesaikan ke pemasok ini.</p>
                    @if (auth()->user()->hasPermission('payables.view'))
                        <div class="mt-4 pt-3 border-t border-zinc-100">
                            <a href="{{ route('payables.index', ['supplier_id' => $supplier->id]) }}" wire:navigate class="inline-flex items-center text-xs font-bold text-accent hover:underline gap-1">
                                <span>Lihat Rincian Tagihan Hutang</span>
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </section>
            </aside>

            <!-- Tabel Riwayat Faktur Penerimaan Barang -->
            <section class="ui-panel overflow-hidden">
                <div class="ui-panel-header">
                    <div>
                        <h2 class="text-base font-bold text-zinc-950">Riwayat Faktur Penerimaan Barang</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Daftar penerimaan barang dagangan dari pemasok ini.</p>
                    </div>
                </div>

                @if ($supplier->receipts->isEmpty())
                    <div class="p-8 text-center text-sm text-zinc-500">
                        Belum ada riwayat faktur penerimaan barang dari pemasok ini.
                    </div>
                @else
                    <div class="ui-table-wrap border-0 shadow-none">
                        <table class="ui-table">
                            <thead>
                                <tr>
                                    <th>Nomor Faktur</th>
                                    <th>Tanggal Terima</th>
                                    <th>Total Nilai Bon</th>
                                    <th>Status Bayar</th>
                                    <th>Status Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($supplier->receipts as $receipt)
                                    <tr>
                                        <td>
                                            <a class="font-bold text-accent hover:underline" href="{{ route('receipts.show', $receipt) }}" wire:navigate>
                                                {{ $receipt->number }}
                                            </a>
                                        </td>
                                        <td class="font-medium text-zinc-600">
                                            {{ $receipt->received_on->translatedFormat('d M Y') }}
                                        </td>
                                        <td>
                                            <strong class="font-extrabold text-zinc-950 text-sm">
                                                Rp{{ number_format($receipt->total_rupiah, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td>
                                            <x-ui.status :value="match(strtolower($receipt->payment_state)){'paid'=>'Lunas','partial'=>'Sebagian','unpaid'=>'Belum Lunas',default=>str($receipt->payment_state)->replace('_', ' ')->title()}" />
                                        </td>
                                        <td>
                                            <x-ui.status :value="match($receipt->status->value){'posted'=>'Diposting','voided'=>'Dibatalkan',default=>$receipt->status->value}" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts::app>
