<x-layouts::app :title="__('Pengguna')">
    <div class="ui-page">
        <x-ui.page-header eyebrow="Administrasi" title="Pengguna" description="Atur akun internal berdasarkan prinsip akses minimum." />
        <div class="rounded-[14px] border border-[#a9c7d6] bg-[#eef7fb] p-4 text-sm leading-6 text-[#24526b]"><strong>Keamanan akun:</strong> akun baru wajib memakai kata sandi minimal 12 karakter dan akan diminta menggantinya saat pertama masuk.</div>

        @if (auth()->user()->hasPermission('users.create'))
            <form method="POST" action="{{ route('admin.users.store') }}" class="ui-panel p-5">
                @csrf
                <h2 class="font-semibold text-zinc-900">Tambah pengguna</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <div><label class="ui-label" for="name">Nama</label><input id="name" name="name" class="ui-input" value="{{ old('name') }}" required></div>
                    <div><label class="ui-label" for="username">Username</label><input id="username" name="username" class="ui-input" value="{{ old('username') }}" required autocomplete="off"></div>
                    <div><label class="ui-label" for="email">Email (opsional)</label><input id="email" name="email" type="email" class="ui-input" value="{{ old('email') }}"></div>
                    <div><label class="ui-label" for="role">Peran</label><select id="role" name="role" class="ui-select" required>@foreach ($roles as $role)<option value="{{ $role->value }}" @selected(old('role') === $role->value)>{{ $role->label() }}</option>@endforeach</select></div>
                    <div><label class="ui-label" for="password">Kata sandi sementara</label><input id="password" name="password" type="password" class="ui-input" minlength="12" required autocomplete="new-password"></div>
                    <div><label class="ui-label" for="password_confirmation">Ulangi kata sandi</label><input id="password_confirmation" name="password_confirmation" type="password" class="ui-input" minlength="12" required autocomplete="new-password"></div>
                </div>
                @if ($errors->any())<div class="mt-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif
                <button type="submit" class="ui-btn ui-btn-primary mt-4"><flux:icon.user-plus class="size-4" /> Tambah pengguna</button>
            </form>
        @endif

        <div class="ui-panel"><div class="ui-table-wrap"><table class="ui-table"><thead><tr><th>Pengguna</th><th>Peran</th><th>Login terakhir</th><th>Status</th><th>Akses</th></tr></thead><tbody>
            @foreach ($users as $user)
                <tr><td><strong class="text-zinc-900">{{ $user->name }}</strong><br><span class="text-xs">{{ '@'.$user->username }}</span></td><td>{{ $user->role->label() }}</td><td>{{ $user->last_login_at?->translatedFormat('d M Y, H.i') ?? 'Belum pernah' }}</td><td><x-ui.status :value="$user->is_active ? 'Aktif' : 'Nonaktif'" /></td><td>
                    @if (auth()->user()->hasPermission('users.update'))<form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex min-w-72 items-center gap-2">@csrf @method('PUT')<select name="role" class="ui-select text-xs">@foreach ($roles as $role)<option value="{{ $role->value }}" @selected($user->role === $role)>{{ $role->label() }}</option>@endforeach</select><select name="is_active" class="ui-select text-xs"><option value="1" @selected($user->is_active)>Aktif</option><option value="0" @selected(! $user->is_active)>Nonaktif</option></select><button class="ui-btn" type="submit">Simpan</button></form>@else - @endif
                    @if (auth()->user()->hasPermission('users.update'))<details class="mt-2"><summary class="cursor-pointer text-xs font-semibold text-accent">Reset kata sandi</summary><form method="POST" action="{{ route('admin.users.password', $user) }}" class="mt-2 grid gap-2">@csrf @method('PUT')<input name="password" type="password" class="ui-input text-xs" placeholder="Kata sandi sementara (min. 12)" minlength="12" required autocomplete="new-password"><input name="password_confirmation" type="password" class="ui-input text-xs" placeholder="Ulangi kata sandi" minlength="12" required autocomplete="new-password"><button class="ui-btn" type="submit">Reset</button></form></details>@endif
                </td></tr>
            @endforeach
        </tbody></table></div></div>
    </div>
</x-layouts::app>
