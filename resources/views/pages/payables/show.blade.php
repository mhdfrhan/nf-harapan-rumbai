<x-layouts::app :title="'Hutang ' . $payable->number">
    <div class="ui-page max-w-5xl space-y-6">
        <x-ui.page-header 
            eyebrow="Keuangan & Pemasok" 
            :title="$payable->number" 
            :description="'Tagihan hutang usaha kepada pihak pemasok ' . $payable->supplier->name . ($payable->due_on ? ' (Jatuh tempo: ' . $payable->due_on->translatedFormat('d F Y') . ')' : '')"
        >
            <x-slot:actions>
                @if($payable->balance_rupiah > 0 && auth()->user()->hasPermission('payable_payments.post'))
                    <a class="ui-btn ui-btn-primary font-bold text-xs" href="{{ route('payables.payments.create', $payable) }}" wire:navigate>
                        <flux:icon.banknotes class="size-4" />
                        <span>Catat Pembayaran Cicilan</span>
                    </a>
                @endif
                <a href="{{ route('payables.index') }}" wire:navigate class="ui-btn text-xs font-semibold">
                    <span>Kembali ke Daftar Hutang</span>
                </a>
            </x-slot:actions>
        </x-ui.page-header>

        @if(session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-medium text-rose-800">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- 3 KPI Cards Keuangan Hutang -->
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-xs">
                <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Nilai Tagihan Awal</span>
                <p class="mt-2 text-2xl font-extrabold text-zinc-950">
                    Rp{{ number_format($payable->principal_rupiah, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-zinc-500">Nilai total belanja faktur bon</p>
            </div>

            <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-xs">
                <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Sudah Terbayar</span>
                <p class="mt-2 text-2xl font-extrabold text-emerald-700">
                    Rp{{ number_format($payable->paid_rupiah, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-zinc-500">Uang yang sudah diserahkan</p>
            </div>

            <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-xs">
                <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Sisa Hutang Belum Lunas</span>
                <p class="mt-2 text-2xl font-extrabold {{ $payable->balance_rupiah > 0 ? 'text-amber-800' : 'text-zinc-500' }}">
                    Rp{{ number_format($payable->balance_rupiah, 0, ',', '.') }}
                </p>
                <div class="mt-1 flex items-center gap-1.5">
                    <x-ui.status :value="match($payable->derivedStatus()->value){
                        'unpaid' => 'Belum Lunas',
                        'partially_paid' => 'Dibayar Sebagian',
                        'paid' => 'Lunas',
                        'overdue' => 'Jatuh Tempo',
                        default => str($payable->derivedStatus()->value)->replace('_', ' ')->title()
                    }"/>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat Cicilan / Pembayaran -->
        <section class="ui-panel overflow-hidden">
            <div class="ui-panel-header">
                <div>
                    <h2 class="text-base font-bold text-zinc-950">Buku Riwayat Pembayaran Tagihan</h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Catatan seluruh pembayaran tunai maupun transfer yang pernah disetorkan.</p>
                </div>
            </div>

            <div class="ui-table-wrap border-0 shadow-none">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nomor Pembayaran</th>
                            <th>Tanggal Bayar</th>
                            <th>Jumlah Disetor</th>
                            <th>Metode Bayar</th>
                            <th>Petugas / Dicatat Oleh</th>
                            <th>Bukti Nota</th>
                            <th>Status / Koreksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payable->payments as $payment)
                            @php($proof = $payment->documents->first() ?? ($loop->first ? $payable->receipt?->documents?->first() : null))
                            <tr>
                                <td>
                                    <span class="font-mono text-xs font-bold text-zinc-900">{{ $payment->number }}</span>
                                </td>
                                <td class="whitespace-nowrap font-medium text-zinc-600">
                                    {{ $payment->paid_on->translatedFormat('d M Y') }}
                                </td>
                                <td>
                                    <strong class="text-sm font-extrabold text-emerald-700">
                                        Rp{{ number_format($payment->amount_rupiah, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <span class="rounded-lg bg-zinc-100 px-2 py-0.5 text-xs font-semibold text-zinc-700">
                                        {{ str($payment->method)->title() }}
                                    </span>
                                </td>
                                <td class="font-medium text-zinc-800">
                                    {{ $payment->creator?->name ?? 'Admin Toko' }}
                                </td>
                                <td>
                                    @if($proof)
                                        <a class="inline-flex items-center gap-1 font-bold text-xs text-accent hover:underline" href="{{ route('documents.download', $proof) }}">
                                            <flux:icon.arrow-down-tray class="size-3.5" />
                                            <span>Unduh Bukti</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-zinc-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <x-ui.status :value="match(strtolower($payment->status)){'posted'=>'Tercatat','voided'=>'Dibatalkan',default=>str($payment->status)->title()}"/>
                                    @if($payment->status === 'posted' && auth()->user()->hasPermission('payable_payments.void'))
                                        <details class="mt-2">
                                            <summary class="cursor-pointer text-xs font-semibold text-rose-700 hover:underline">Batalkan</summary>
                                            <form method="POST" action="{{ route('payables.payments.void', [$payable, $payment]) }}" class="mt-2 space-y-2 p-3 rounded-xl border border-rose-200 bg-rose-50/50">
                                                @csrf
                                                <input type="hidden" name="idempotency_key" value="{{ (string)\Illuminate\Support\Str::uuid() }}">
                                                <label class="ui-label text-[11px] text-rose-900 block">Alasan Pembatalan</label>
                                                <textarea name="reason" class="ui-textarea text-xs min-w-64" minlength="10" maxlength="500" placeholder="Alasan koreksi transaksi..." required></textarea>
                                                <button class="ui-btn ui-btn-danger text-xs font-bold w-full" type="submit">
                                                    Konfirmasi Batalkan
                                                </button>
                                            </form>
                                        </details>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-zinc-500 text-sm">
                                    Belum ada catatan setoran pembayaran cicilan untuk tagihan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts::app>
