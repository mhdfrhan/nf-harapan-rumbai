@props([
    'name' => null,
    'show' => null,
    'maxWidth' => 'md',
    'title' => null,
    'description' => null,
])

@php
$maxWidthClasses = match ($maxWidth) {
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    default => 'sm:max-w-md',
};

$showExpression = $show ?? ($name ? '$wire.entangle(\''.$name.'\')' : 'show');
@endphp

<div
    x-data="{ show: {{ $showExpression }} }"
    x-init="$watch('{{ $showExpression }}', value => show = value); $watch('show', value => {{ $showExpression }} = value)"
    x-show="show"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="{{ $name ? $name.'-title' : 'modal-title' }}"
    role="dialog"
    aria-modal="true"
>
    <!-- Smooth Backdrop -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-250"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm transition-opacity"
        @click="show = false"
        aria-hidden="true"
    ></div>

    <!-- Centered Modal Dialog -->
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div
            x-show="show"
            x-transition:enter="ease-out duration-250"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 {{ $maxWidthClasses }}"
            @click.outside="show = false"
        >
            @if ($title)
                <div class="flex items-start justify-between border-b border-zinc-200 px-5 py-4 dark:border-zinc-800">
                    <div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white" id="{{ $name ? $name.'-title' : 'modal-title' }}">
                            {{ $title }}
                        </h3>
                        @if ($description)
                            <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $description }}
                            </p>
                        @endif
                    </div>
                    <button
                        type="button"
                        @click="show = false"
                        class="rounded-lg p-1 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                        aria-label="Tutup modal"
                    >
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <div class="px-5 py-4">
                {{ $slot }}
            </div>

            @if (isset($footer))
                <div class="flex items-center justify-end gap-2 border-t border-zinc-200 bg-zinc-50 px-5 py-3 dark:border-zinc-800 dark:bg-zinc-900/60">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
