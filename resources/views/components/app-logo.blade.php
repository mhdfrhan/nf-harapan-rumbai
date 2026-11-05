@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand :name="config('store.name', 'Toko NF Harapan')" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-10 items-center justify-center rounded-xl bg-accent text-white shadow-xs">
            <x-app-logo-icon class="text-white" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="config('store.name', 'Toko NF Harapan')" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-10 items-center justify-center rounded-xl bg-accent text-white shadow-xs">
            <x-app-logo-icon class="text-white" />
        </x-slot>
    </flux:brand>
@endif
