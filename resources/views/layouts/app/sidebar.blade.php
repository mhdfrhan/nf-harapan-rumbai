@php($storeProfile = app(\App\Services\StoreProfile::class)->get())
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-cream-100 text-zinc-900 antialiased">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200/80 bg-cream-50">
            <flux:sidebar.header class="pb-2">
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="space-y-4">
                <flux:sidebar.group :heading="__('Operasional')" class="grid gap-1">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Ringkasan') }}
                    </flux:sidebar.item>
                    @if (auth()->user()->hasPermission('sales.create'))
                        <flux:sidebar.item icon="shopping-cart" :href="route('pos.index')" :current="request()->routeIs('pos.*')" wire:navigate>{{ __('Kasir (POS)') }}</flux:sidebar.item>
                    @endif
                    @if (auth()->user()->hasPermission('sales.view_all') || auth()->user()->hasPermission('sales.view_own'))
                        <flux:sidebar.item icon="receipt-percent" :href="route('sales.index')" :current="request()->routeIs('sales.*')" wire:navigate>{{ __('Riwayat Penjualan') }}</flux:sidebar.item>
                    @endif
                </flux:sidebar.group>

                @if (auth()->user()->hasPermission('products.view') || auth()->user()->hasPermission('stock.view') || auth()->user()->hasPermission('purchases.view'))
                    <flux:sidebar.group :heading="__('Barang & Gudang')" class="grid gap-1">
                        @if (auth()->user()->hasPermission('products.view'))
                            <flux:sidebar.item icon="archive-box" :href="route('catalog.products.index')" :current="request()->routeIs('catalog.*')" wire:navigate>{{ __('Katalog Produk') }}</flux:sidebar.item>
                        @endif
                        @if (auth()->user()->hasPermission('stock.view'))
                            <flux:sidebar.item icon="arrows-right-left" :href="route('inventory.index')" :current="request()->routeIs('inventory.*')" wire:navigate>{{ __('Stok Persediaan') }}</flux:sidebar.item>
                        @endif
                        @if (auth()->user()->hasPermission('purchases.view'))
                            <flux:sidebar.item icon="truck" :href="route('receipts.index')" :current="request()->routeIs('receipts.*')" wire:navigate>{{ __('Penerimaan Barang') }}</flux:sidebar.item>
                        @endif
                    </flux:sidebar.group>
                @endif

                @if (auth()->user()->hasPermission('suppliers.manage') || auth()->user()->hasPermission('purchases.view') || auth()->user()->hasPermission('payables.view'))
                    <flux:sidebar.group :heading="__('Pemasok & Hutang')" class="grid gap-1">
                        @if (auth()->user()->hasPermission('suppliers.manage') || auth()->user()->hasPermission('purchases.view'))
                            <flux:sidebar.item icon="building-storefront" :href="route('suppliers.index')" :current="request()->routeIs('suppliers.*')" wire:navigate>{{ __('Daftar Pemasok') }}</flux:sidebar.item>
                        @endif
                        @if (auth()->user()->hasPermission('payables.view'))
                            <flux:sidebar.item icon="banknotes" :href="route('payables.index')" :current="request()->routeIs('payables.*')" wire:navigate>{{ __('Hutang Pemasok') }}</flux:sidebar.item>
                        @endif
                    </flux:sidebar.group>
                @endif

                @if (auth()->user()->hasPermission('reports.sales') || auth()->user()->hasPermission('reports.stock') || auth()->user()->hasPermission('audit.view'))
                    <flux:sidebar.group :heading="__('Laporan & Analisis')" class="grid gap-1">
                        @if (auth()->user()->hasPermission('reports.sales'))
                            <flux:sidebar.item icon="chart-bar" :href="route('reports.sales')" :current="request()->routeIs('reports.*')" wire:navigate>{{ __('Laporan & Laba') }}</flux:sidebar.item>
                        @elseif (auth()->user()->hasPermission('reports.stock'))
                            <flux:sidebar.item icon="chart-bar" :href="route('reports.inventory')" :current="request()->routeIs('reports.*')" wire:navigate>{{ __('Laporan & Laba') }}</flux:sidebar.item>
                        @endif
                        @if (auth()->user()->hasPermission('audit.view'))
                            <flux:sidebar.item icon="shield-check" :href="route('admin.audit')" :current="request()->routeIs('admin.audit')" wire:navigate>{{ __('Audit Aktivitas') }}</flux:sidebar.item>
                        @endif
                    </flux:sidebar.group>
                @endif

                @if (auth()->user()->hasPermission('users.view') || auth()->user()->hasPermission('settings.manage') || auth()->user()->hasPermission('settings.view'))
                    <flux:sidebar.group :heading="__('Kelola & Pengaturan')" class="grid gap-1">
                        @if (auth()->user()->hasPermission('users.view'))
                            <flux:sidebar.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>{{ __('Data Pengguna') }}</flux:sidebar.item>
                        @endif
                        @if (auth()->user()->hasPermission('settings.manage') || auth()->user()->hasPermission('settings.view'))
                            <flux:sidebar.item icon="cog-6-tooth" :href="route('settings.store')" :current="request()->routeIs('settings.store*')" wire:navigate>{{ __('Pengaturan Toko') }}</flux:sidebar.item>
                        @endif
                    </flux:sidebar.group>
                @endif
            </flux:sidebar.nav>

            <flux:spacer />

            <div class="mx-2 mb-3 rounded-xl border border-zinc-200/90 bg-white p-3 shadow-2xs">
                <div class="flex items-center gap-2">
                    <span class="flex size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-zinc-900 truncate">{{ $storeProfile['name'] }}</span>
                </div>
                <div class="mt-1 text-xs text-zinc-500 leading-relaxed">
                    <span>{{ $storeProfile['open_days'] }}</span><br>
                    <span class="font-medium text-zinc-700">{{ $storeProfile['open_hours'] }}</span>
                </div>
            </div>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile Header Bar -->
        <flux:header class="lg:hidden border-b border-zinc-200/80 bg-white px-4 py-2.5">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <div class="flex items-center gap-2">
                <x-app-logo-icon class="size-7 rounded-lg bg-accent text-[10px]" />
                <span class="text-sm font-bold text-zinc-900 truncate">{{ config('store.name', 'Toko NF Harapan') }}</span>
            </div>

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu class="w-60">
                    <div class="px-3 py-2 text-start text-sm">
                        <span class="font-bold text-zinc-900 block truncate">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-zinc-500 block truncate">{{ auth()->user()->email }}</span>
                        <span class="mt-1 inline-flex items-center rounded-full bg-accent/10 px-2 py-0.5 text-[10px] font-semibold text-accent">
                            {{ auth()->user()->role?->label() ?? 'Pengguna' }}
                        </span>
                    </div>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Pengaturan Akun') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer text-danger"
                            data-test="logout-button"
                        >
                            {{ __('Keluar dari Aplikasi') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
