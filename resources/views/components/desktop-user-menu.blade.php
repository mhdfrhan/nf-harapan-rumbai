<flux:dropdown position="bottom" align="start">
    <flux:sidebar.profile
        :name="auth()->user()->name"
        :initials="auth()->user()->initials()"
        icon:trailing="chevrons-up-down"
        data-test="sidebar-menu-button"
    />

    <flux:menu class="w-64">
        <div class="flex items-center gap-3 px-2 py-2.5 text-start text-sm">
            <flux:avatar
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
                class="size-10"
            />
            <div class="grid flex-1 text-start text-sm leading-tight">
                <span class="font-bold text-zinc-900 truncate">{{ auth()->user()->name }}</span>
                <span class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</span>
                <div class="mt-1">
                    <span class="inline-flex items-center rounded-full bg-accent/10 px-2 py-0.5 text-[11px] font-semibold text-accent">
                        {{ auth()->user()->role?->label() ?? 'Pengguna' }}
                    </span>
                </div>
            </div>
        </div>

        <flux:menu.separator />

        <flux:menu.radio.group>
            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                {{ __('Pengaturan Akun') }}
            </flux:menu.item>

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
        </flux:menu.radio.group>
    </flux:menu>
</flux:dropdown>
