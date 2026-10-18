@props([
    'eyebrow' => null,
    'title',
    'description' => null,
])

<header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div class="max-w-3xl">
        @if ($eyebrow)
            <p class="mb-1 text-xs font-semibold uppercase tracking-[0.08em] text-accent">{{ $eyebrow }}</p>
        @endif
        <h1 class="text-2xl font-semibold tracking-[-0.035em] text-zinc-950 sm:text-[28px]">{{ $title }}</h1>
        @if ($description)
            <p class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600">{{ $description }}</p>
        @endif
    </div>
    @if (isset($actions))
        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endif
</header>
