<x-layouts::auth :title="__('Masuk')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Masuk ke sistem')" :description="__('Gunakan akun internal yang diberikan oleh pemilik toko.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Username -->
            <flux:input
                name="username"
                :label="__('Nama pengguna')"
                :value="old('username')"
                type="text"
                required
                maxlength="50"
                autofocus
                autocomplete="username"
                placeholder="contoh: kasir.pagi"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Kata sandi')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Kata sandi')"
                    viewable
                />

            </div>

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Masuk') }}
                </flux:button>
            </div>
        </form>
        <p class="text-center text-sm leading-6 text-zinc-600">
            Akun baru dan pemulihan akses dikelola oleh pemilik toko.
        </p>
    </div>
</x-layouts::auth>
