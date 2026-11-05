<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-cream-100 text-zinc-900 antialiased selection:bg-accent selection:text-white">
        <div class="flex min-h-svh flex-col items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="flex w-full max-w-lg flex-col items-center">
                <a href="{{ route('home') }}" class="mb-6 flex flex-col items-center gap-2.5 transition-transform hover:scale-105" wire:navigate>
                    <span class="flex size-14 items-center justify-center rounded-2xl bg-accent text-white shadow-md">
                        <x-app-logo-icon class="text-base font-extrabold tracking-wider" />
                    </span>
                    <div class="text-center">
                        <span class="text-base font-extrabold tracking-tight text-zinc-950 block">{{ config('store.name', 'Toko NF Harapan Rumbai') }}</span>
                        <span class="text-xs text-zinc-500 font-medium">Sistem Informasi Kasir & Manajemen Stok</span>
                    </div>
                </a>

                <div class="w-full rounded-3xl border border-zinc-200/90 bg-white p-6 sm:p-8 shadow-xs">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
