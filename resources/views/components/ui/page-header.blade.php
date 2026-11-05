@props([
    'eyebrow' => null,
    'title',
    'description' => null,
])

<header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="max-w-3xl">
        @if ($eyebrow)
            <div class="mb-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold text-accent">
                    <span class="size-1.5 rounded-full bg-accent"></span>
                    <span>{{ $eyebrow }}</span>
                </span>
            </div>
        @endif
        <h1 class="text-2xl font-bold tracking-tight text-zinc-950 sm:text-3xl">{{ $title }}</h1>
        @if ($description)
            <p class="mt-2 text-sm leading-relaxed text-zinc-600 sm:text-base">{{ $description }}</p>
        @endif
    </div>
    @if (isset($actions))
        <div class="flex flex-wrap items-center gap-2.5 sm:shrink-0">{{ $actions }}</div>
    @endif
</header>
