@props(['value'])

@php
    $normalized = strtolower(trim($value));
    [$pillClass, $dotClass] = match ($normalized) {
        'lunas', 'diposting', 'aktif', 'aman', 'tersedia', 'selesai' => [
            'bg-emerald-50 text-emerald-800 border-emerald-200/80',
            'bg-emerald-500'
        ],
        'belum lunas', 'jatuh tempo', 'stok menipis', 'menipis', 'peringatan' => [
            'bg-amber-50 text-amber-800 border-amber-200/80',
            'bg-amber-500'
        ],
        'dibayar sebagian', 'draft', 'tertahan', 'sebagian' => [
            'bg-sky-50 text-sky-800 border-sky-200/80',
            'bg-sky-500'
        ],
        'dibatalkan', 'nonaktif', 'habis', 'batal' => [
            'bg-rose-50 text-rose-800 border-rose-200/80',
            'bg-rose-500'
        ],
        default => [
            'bg-cream-200 text-zinc-700 border-zinc-300/80',
            'bg-zinc-500'
        ],
    };
@endphp

<span {{ $attributes->class("inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold {$pillClass} shadow-2xs") }}>
    <span class="size-1.5 rounded-full {{ $dotClass }}"></span>
    <span>{{ $value }}</span>
</span>
