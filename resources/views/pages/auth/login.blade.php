<x-layouts::auth :title="__('Masuk ke Sistem')">
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-950">Masuk ke Sistem Kasir</h1>
            <p class="mt-1.5 text-xs sm:text-sm text-zinc-600">Gunakan akun pengguna yang telah didaftarkan oleh pemilik toko.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Username -->
            <div>
                <label class="ui-label" for="username">Nama Pengguna (Username)</label>
                <input
                    id="username"
                    name="username"
                    class="ui-input text-base font-medium"
                    value="{{ old('username') }}"
                    type="text"
                    required
                    maxlength="50"
                    autofocus
                    autocomplete="username"
                    placeholder="Contoh: owner atau kasir_pagi"
                >
            </div>

            <!-- Password -->
            <div>
                <label class="ui-label" for="password">Kata Sandi (Password)</label>
                <input
                    id="password"
                    name="password"
                    class="ui-input text-base font-medium"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan kata sandi akun"
                >
            </div>

            <div class="pt-1">
                <button type="submit" class="ui-btn ui-btn-primary w-full font-bold text-sm py-3 shadow-sm" data-test="login-button">
                    <span>Masuk ke Akun Saya</span>
                </button>
            </div>
        </form>

        <p class="text-center text-xs leading-relaxed text-zinc-500">
            Lupa kata sandi atau butuh akses baru? Silakan hubungi pemilik toko untuk bantuan reset akun.
        </p>

        @if (! app()->isProduction())
            <div class="rounded-2xl border border-dashed border-accent/40 bg-cream-50/70 p-4 text-xs">
                <div class="flex items-center gap-2 font-bold text-accent">
                    <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Masuk Cepat Mode Demo / Pengujian</span>
                </div>
                <p class="mt-1 text-zinc-500 text-xs">
                    Pilih salah satu peran di bawah untuk langsung terisi otomatis:
                </p>

                <div class="mt-3 grid gap-2.5 sm:grid-cols-3">
                    <button 
                        type="button" 
                        onclick="quickLogin('owner', 'password')"
                        class="flex flex-col items-start rounded-xl border border-zinc-200 bg-white p-3 text-left shadow-2xs hover:border-accent hover:shadow-xs transition active:scale-95"
                    >
                        <span class="font-bold text-zinc-950 text-xs">Ibu Emi Erawati</span>
                        <span class="mt-0.5 text-xs font-semibold text-accent">Pemilik Toko</span>
                        <span class="text-[11px] text-zinc-400">Akses Penuh & Laba</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="quickLogin('kasir_pagi', 'password')"
                        class="flex flex-col items-start rounded-xl border border-zinc-200 bg-white p-3 text-left shadow-2xs hover:border-accent hover:shadow-xs transition active:scale-95"
                    >
                        <span class="font-bold text-zinc-950 text-xs">Dinda Permata</span>
                        <span class="mt-0.5 text-xs font-semibold text-emerald-700">Kasir Toko (POS)</span>
                        <span class="text-[11px] text-zinc-400">Transaksi Kasir</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="quickLogin('petugas_stok', 'password')"
                        class="flex flex-col items-start rounded-xl border border-zinc-200 bg-white p-3 text-left shadow-2xs hover:border-accent hover:shadow-xs transition active:scale-95"
                    >
                        <span class="font-bold text-zinc-950 text-xs">Rina Wulandari</span>
                        <span class="mt-0.5 text-xs font-semibold text-sky-700">Admin Gudang</span>
                        <span class="text-[11px] text-zinc-400">Penerimaan & Opname</span>
                    </button>
                </div>
            </div>

            <script>
                function quickLogin(username, password) {
                    const u = document.querySelector('input[name="username"]');
                    const p = document.querySelector('input[name="password"]');
                    if (u && p) {
                        u.value = username;
                        p.value = password;
                        const form = u.closest('form') || document.querySelector('form');
                        if (form) form.submit();
                    }
                }
            </script>
        @endif
    </div>
</x-layouts::auth>
