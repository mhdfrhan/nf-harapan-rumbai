@props([
    'emptyTitle' => 'Belum ada data',
    'emptyDescription' => 'Data akan tampil di sini setelah ditambahkan.',
])

@php($state = request()->query('state', 'normal'))

@if ($state === 'loading')
    <div class="ui-panel p-5" aria-busy="true" aria-label="Memuat data">
        <div class="space-y-4">
            <div class="ui-skeleton h-5 w-40"></div>
            @foreach (range(1, 5) as $row)
                <div class="grid grid-cols-4 gap-4">
                    <div class="ui-skeleton col-span-2 h-11"></div>
                    <div class="ui-skeleton h-11"></div>
                    <div class="ui-skeleton h-11"></div>
                </div>
            @endforeach
        </div>
    </div>
@elseif ($state === 'empty')
    <div class="ui-panel flex min-h-72 flex-col items-center justify-center px-6 py-12 text-center">
        <div class="flex size-12 items-center justify-center rounded-full bg-[#e2ece6] text-accent">
            <flux:icon.inbox class="size-6" />
        </div>
        <h2 class="mt-4 text-base font-semibold text-zinc-900">{{ $emptyTitle }}</h2>
        <p class="mt-1 max-w-md text-sm leading-6 text-zinc-600">{{ $emptyDescription }}</p>
    </div>
@elseif ($state === 'error')
    <div class="ui-panel flex min-h-72 flex-col items-center justify-center px-6 py-12 text-center" role="alert">
        <div class="flex size-12 items-center justify-center rounded-full bg-[#fbeaea] text-danger">
            <flux:icon.exclamation-triangle class="size-6" />
        </div>
        <h2 class="mt-4 text-base font-semibold text-zinc-900">Data belum dapat dimuat</h2>
        <p class="mt-1 max-w-md text-sm leading-6 text-zinc-600">Periksa koneksi lalu coba kembali. Data yang sudah tersimpan tetap aman.</p>
        <a class="ui-btn mt-5" href="{{ url()->current() }}">Coba lagi</a>
    </div>
@elseif ($state === 'offline')
    <div class="ui-panel border-[#dfbd76] bg-[#fff9e8] p-4" role="status">
        <div class="flex gap-3">
            <flux:icon.wifi class="mt-0.5 size-5 text-warning" />
            <div>
                <p class="text-sm font-semibold text-[#704600]">Koneksi internet terputus</p>
                <p class="mt-1 text-sm text-[#755b27]">Halaman menampilkan data terakhir. Sambungkan kembali sebelum menyimpan transaksi.</p>
            </div>
        </div>
    </div>
    {{ $slot }}
@else
    {{ $slot }}
@endif
