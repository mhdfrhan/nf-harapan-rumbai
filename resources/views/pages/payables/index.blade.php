<x-layouts::app :title="__('Hutang Pemasok')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Keuangan & Pemasok" 
            title="Catatan Hutang Usaha Toko" 
            description="Pantau tanggal jatuh tempo tagihan pemasok, sisa hutang tempo yang belum lunas, dan riwayat pembayaran cicilan."
        />

        <!-- Filter Toolbar -->
        <section class="ui-panel p-4 sm:p-5">
            <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5 items-end">
                <div>
                    <label class="ui-label text-xs mb-1 block">Pilih Pemasok</label>
                    <select name="supplier_id" class="ui-select text-xs">
                        <option value="">Semua Pemasok</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected((string)request('supplier_id') === (string)$supplier->id)>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="ui-label text-xs mb-1 block">Status Pembayaran</label>
                    <select name="status" class="ui-select text-xs">
                        <option value="">Semua Status</option>
                        @foreach(['unpaid' => 'Belum Dibayar', 'partially_paid' => 'Dibayar Sebagian', 'paid' => 'Lunas', 'overdue' => 'Lewat Jatuh Tempo'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="ui-label text-xs mb-1 block">Jatuh Tempo Dari</label>
                    <input name="from" type="date" class="ui-input text-xs" value="{{ request('from') }}">
                </div>

                <div>
                    <label class="ui-label text-xs mb-1 block">Jatuh Tempo Sampai</label>
                    <input name="to" type="date" class="ui-input text-xs" value="{{ request('to') }}">
                </div>

                <div class="flex items-center gap-2">
                    <button class="ui-btn ui-btn-primary text-xs font-bold w-full" type="submit">
                        Terapkan Filter
                    </button>
                    @if(request('supplier_id') || request('status') || request('from') || request('to'))
                        <a href="{{ route('payables.index') }}" wire:navigate class="ui-btn text-xs font-semibold">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </section>

        <!-- Tabel Daftar Hutang Usaha -->
        <section class="ui-panel overflow-hidden">
            <div class="ui-table-wrap">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nomor Hutang</th>
                            <th>Nama Pemasok</th>
                            <th>Tanggal Jatuh Tempo</th>
                            <th>Nilai Tagihan Awal</th>
                            <th>Sudah Terbayar</th>
                            <th>Sisa Hutang Toko</th>
                            <th>Status Tagihan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payables as $payable)
                            <tr>
                                <td>
                                    <a class="font-bold text-accent hover:underline flex items-center gap-1" href="{{ route('payables.show', $payable) }}" wire:navigate>
                                        <span>{{ $payable->number }}</span>
                                    </a>
                                </td>
                                <td>
                                    <strong class="text-sm font-bold text-zinc-900 block">{{ $payable->supplier?->name }}</strong>
                                </td>
                                <td class="whitespace-nowrap font-medium text-zinc-600">
                                    {{ $payable->due_on ? $payable->due_on->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td>Rp{{ number_format($payable->principal_rupiah, 0, ',', '.') }}</td>
                                <td class="text-emerald-700 font-semibold">Rp{{ number_format($payable->paid_rupiah, 0, ',', '.') }}</td>
                                <td>
                                    <strong class="text-sm font-extrabold {{ $payable->balance_rupiah > 0 ? 'text-amber-800' : 'text-zinc-500' }}">
                                        Rp{{ number_format($payable->balance_rupiah, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <x-ui.status :value="match($payable->derivedStatus()->value){
                                        'unpaid' => 'Belum Lunas',
                                        'partially_paid' => 'Dibayar Sebagian',
                                        'paid' => 'Lunas',
                                        'overdue' => 'Jatuh Tempo',
                                        default => str($payable->derivedStatus()->value)->replace('_', ' ')->title()
                                    }"/>
                                </td>
                                <td class="text-right">
                                    @if($payable->balance_rupiah > 0 && auth()->user()->hasPermission('payable_payments.post'))
                                        <a class="ui-btn ui-btn-primary text-xs font-bold py-1.5 px-3" href="{{ route('payables.payments.create', $payable) }}" wire:navigate>
                                            Bayar
                                        </a>
                                    @else
                                        <a class="ui-btn text-xs font-semibold py-1.5 px-3" href="{{ route('payables.show', $payable) }}" wire:navigate>
                                            Rincian
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-10 text-center text-zinc-500">
                                    <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400 mb-2">
                                        <flux:icon.banknotes class="size-6" />
                                    </div>
                                    <p class="font-semibold text-zinc-700 text-sm">Tidak Ada Tagihan Hutang</p>
                                    <p class="text-xs text-zinc-400 mt-1">Seluruh tagihan belanja barang tempo yang belum lunas akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $payables->links() }}
        </div>
    </div>
</x-layouts::app>
