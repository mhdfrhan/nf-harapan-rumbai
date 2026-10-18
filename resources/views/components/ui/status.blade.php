@props(['value'])

@php
    $tone = match (strtolower($value)) {
        'lunas', 'diposting', 'aktif', 'aman', 'tersedia' => 'border-[#9cc8af] bg-[#e7f4ec] text-[#185737]',
        'belum lunas', 'jatuh tempo', 'stok menipis' => 'border-[#dfbd76] bg-[#fff4d6] text-[#704600]',
        'dibayar sebagian', 'draft', 'tertahan' => 'border-[#a9c7d6] bg-[#e8f3f8] text-[#24526b]',
        'dibatalkan', 'nonaktif', 'habis' => 'border-[#dcb0ad] bg-[#fbeaea] text-[#8b2823]',
        default => 'border-zinc-300 bg-cream-200 text-zinc-700',
    };
@endphp

<span {{ $attributes->class("inline-flex min-h-7 items-center rounded-full border px-2.5 py-1 text-xs font-semibold {$tone}") }}>
    {{ $value }}
</span>
