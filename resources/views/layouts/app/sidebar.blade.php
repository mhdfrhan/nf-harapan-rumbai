@php($storeProfile = app(\App\Services\StoreProfile::class)->get())
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-cream-100 text-zinc-900">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-300 bg-cream-50">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Operasional')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Ringkasan') }}
                    </flux:sidebar.item>
                    @if (auth()->user()->hasPermission('sales.create'))
                        <flux:sidebar.item icon="shopping-cart" :href="route('pos.index')" :current="request()->routeIs('pos.*')" wire:navigate>{{ __('Kasir') }}</flux:sidebar.item>
                    @endif
                    @if (auth()->user()->hasPermission('sales.view_all') || auth()->user()->hasPermission('sales.view_own'))
                        <flux:sidebar.item icon="receipt-percent" :href="route('sales.index')" :current="request()->routeIs('sales.*')" wire:navigate>{{ __('Penjualan') }}</flux:sidebar.item>
                    @endif
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Barang dan stok')" class="grid">
                    @if (auth()->user()->hasPermission('products.view'))
                        <flux:sidebar.item icon="archive-box" :href="route('catalog.products.index')" :current="request()->routeIs('catalog.*')" wire:navigate>{{ __('Produk') }}</flux:sidebar.item>
                    @endif
                    @if (auth()->user()->hasPermission('stock.view'))
                        <flux:sidebar.item icon="arrows-right-left" :href="route('inventory.index')" :current="request()->routeIs('inventory.*')" wire:navigate>{{ __('Persediaan') }}</flux:sidebar.item>
                    @endif
                    @if (auth()->user()->hasPermission('purchases.view'))
                        <flux:sidebar.item icon="truck" :href="route('receipts.index')" :current="request()->routeIs('receipts.*')" wire:navigate>{{ __('Penerimaan') }}</flux:sidebar.item>
                    @endif
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Pemasok dan kewajiban')" class="grid">
                    @if (auth()->user()->hasPermission('suppliers.manage') || auth()->user()->hasPermission('purchases.view'))
                        <flux:sidebar.item icon="building-storefront" :href="route('suppliers.index')" :current="request()->routeIs('suppliers.*')" wire:navigate>{{ __('Pemasok') }}</flux:sidebar.item>
                    @endif
                    @if (auth()->user()->hasPermission('payables.view'))
                        <flux:sidebar.item icon="banknotes" :href="route('payables.index')" :current="request()->routeIs('payables.*')" wire:navigate>{{ __('Kewajiban') }}</flux:sidebar.item>
                    @endif
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Analisis')" class="grid">
                    @if (auth()->user()->hasPermission('reports.sales'))
                        <flux:sidebar.item icon="chart-bar" :href="route('reports.sales')" :current="request()->routeIs('reports.*')" wire:navigate>{{ __('Laporan') }}</flux:sidebar.item>
                    @elseif (auth()->user()->hasPermission('reports.stock'))
                        <flux:sidebar.item icon="chart-bar" :href="route('reports.inventory')" :current="request()->routeIs('reports.*')" wire:navigate>{{ __('Laporan') }}</flux:sidebar.item>
                    @endif
                    @if (auth()->user()->hasPermission('audit.view'))
                        <flux:sidebar.item icon="shield-check" :href="route('admin.audit')" :current="request()->routeIs('admin.audit')" wire:navigate>{{ __('Audit aktivitas') }}</flux:sidebar.item>
                    @endif
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Administrasi')" class="grid">
                    @if (auth()->user()->hasPermission('users.view'))
                        <flux:sidebar.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>{{ __('Pengguna') }}</flux:sidebar.item>
                    @endif
                    @if (auth()->user()->hasPermission('settings.manage') || auth()->user()->hasPermission('settings.view'))
                        <flux:sidebar.item icon="cog-6-tooth" :href="route('settings.store')" :current="request()->routeIs('settings.store*')" wire:navigate>{{ __('Pengaturan toko') }}</flux:sidebar.item>
                    @endif
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <div class="mx-2 mb-3 rounded-[10px] border border-zinc-300 bg-cream-100 px-3 py-2.5 text-xs leading-relaxed text-zinc-600">
                <span class="font-semibold text-zinc-800">{{ $storeProfile['name'] }}</span><br>
                {{ $storeProfile['open_days'] }}, {{ $storeProfile['open_hours'] }}
            </div>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Pengaturan akun') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Keluar') }}
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
