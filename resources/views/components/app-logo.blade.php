@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand :name="config('store.name')" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-9 items-center justify-center rounded-[10px] bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="text-cream-50" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="config('store.name')" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-9 items-center justify-center rounded-[10px] bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="text-cream-50" />
        </x-slot>
    </flux:brand>
@endif
