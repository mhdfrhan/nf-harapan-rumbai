<x-layouts::app :title="__('Kelola Pengguna')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Administrasi Sistem" 
            title="Kelola Data Pengguna" 
            description="Atur akun kasir, admin persediaan, dan auditor toko berdasarkan prinsip hak akses minimum." 
        />

        <div class="rounded-2xl border border-sky-200 bg-sky-50/80 p-4 text-xs sm:text-sm leading-relaxed text-sky-950 flex items-start gap-3">
            <svg class="size-5 shrink-0 text-sky-700 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <div>
                <strong class="font-bold">Keamanan Akun:</strong> Akun baru wajib menggunakan kata sandi minimal 12 karakter dan akan otomatis diminta menggantinya saat pertama kali masuk ke aplikasi.
            </div>
        </div>

        @if (auth()->user()->hasPermission('users.create'))
            <details class="ui-panel overflow-hidden group">
                <summary class="ui-panel-header cursor-pointer select-none">
                    <div class="flex items-center gap-2">
                        <div class="flex size-7 items-center justify-center rounded-lg bg-accent/10 text-accent">
                            <flux:icon.user-plus class="size-4" />
                        </div>
                        <h2 class="text-sm font-bold text-zinc-950">+ Daftarkan Pengguna Baru</h2>
                    </div>
                    <span class="text-xs font-semibold text-accent group-open:rotate-180 transition-transform">
                        Buka Formulir
                    </span>
                </summary>

                <form method="POST" action="{{ route('admin.users.store') }}" class="p-5 space-y-4 border-t border-zinc-100 bg-cream-50/40">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <div>
                            <label class="ui-label" for="name">Nama Lengkap</label>
                            <input id="name" name="name" class="ui-input" placeholder="Contoh: Dinda Permata" value="{{ old('name') }}" required>
                        </div>
                        <div>
                            <label class="ui-label" for="username">Username Login</label>
                            <input id="username" name="username" class="ui-input font-mono text-xs" placeholder="Contoh: dinda" value="{{ old('username') }}" required autocomplete="off">
                        </div>
                        <div>
                            <label class="ui-label" for="email">Alamat Email (Opsional)</label>
                            <input id="email" name="email" type="email" class="ui-input" placeholder="contoh@gmail.com" value="{{ old('email') }}">
                        </div>
                        <div>
                            <label class="ui-label" for="role">Peran & Hak Akses</label>
                            <select id="role" name="role" class="ui-select" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->value }}" @selected(old('role') === $role->value)>{{ $role->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="ui-label" for="password">Kata Sandi Sementara</label>
                            <input id="password" name="password" type="password" class="ui-input" minlength="12" placeholder="Minimal 12 karakter" required autocomplete="new-password">
                        </div>
                        <div>
                            <label class="ui-label" for="password_confirmation">Ulangi Kata Sandi</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="ui-input" minlength="12" placeholder="Ulangi kata sandi" required autocomplete="new-password">
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800 font-medium">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="pt-2">
                        <button type="submit" class="ui-btn ui-btn-primary font-bold text-xs">
                            <flux:icon.user-plus class="size-4" />
                            <span>Simpan Pengguna Baru</span>
                        </button>
                    </div>
                </form>
            </details>
        @endif

        <!-- Tabel Daftar Pengguna -->
        <section class="ui-panel overflow-hidden">
            <div class="ui-table-wrap">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nama & Username</th>
                            <th>Peran / Jabatan</th>
                            <th>Waktu Login Terakhir</th>
                            <th>Status Akun</th>
                            <th>Pengaturan Hak & Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <strong class="text-sm font-bold text-zinc-950 block">{{ $user->name }}</strong>
                                    <span class="text-xs font-mono text-zinc-500">{{ '@' . $user->username }}</span>
                                </td>
                                <td>
                                    <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-bold text-accent">
                                        {{ $user->role->label() }}
                                    </span>
                                </td>
                                <td class="font-medium text-zinc-600 whitespace-nowrap">
                                    {{ $user->last_login_at?->translatedFormat('d M Y, H.i') ?? 'Belum pernah login' }}
                                </td>
                                <td>
                                    <x-ui.status :value="$user->is_active ? 'Aktif' : 'Nonaktif'" />
                                </td>
                                <td>
                                    @if (auth()->user()->hasPermission('users.update'))
                                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex min-w-72 items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" class="ui-select text-xs py-1">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->value }}" @selected($user->role === $role)>{{ $role->label() }}</option>
                                                @endforeach
                                            </select>
                                            <select name="is_active" class="ui-select text-xs py-1">
                                                <option value="1" @selected($user->is_active)>Aktif</option>
                                                <option value="0" @selected(! $user->is_active)>Nonaktif</option>
                                            </select>
                                            <button class="ui-btn text-xs font-semibold py-1 px-3" type="submit">Simpan</button>
                                        </form>

                                        <details class="mt-2">
                                            <summary class="cursor-pointer text-xs font-semibold text-accent hover:underline">Reset Kata Sandi</summary>
                                            <form method="POST" action="{{ route('admin.users.password', $user) }}" class="mt-2 p-3 rounded-xl border border-zinc-200 bg-cream-50/50 space-y-2">
                                                @csrf
                                                @method('PUT')
                                                <input name="password" type="password" class="ui-input text-xs" placeholder="Kata sandi baru (min. 12)" minlength="12" required autocomplete="new-password">
                                                <input name="password_confirmation" type="password" class="ui-input text-xs" placeholder="Ulangi kata sandi" minlength="12" required autocomplete="new-password">
                                                <button class="ui-btn text-xs font-bold w-full" type="submit">Konfirmasi Reset Kata Sandi</button>
                                            </form>
                                        </details>
                                    @else
                                        <span class="text-xs text-zinc-400">Tidak ada izin ubah</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts::app>
