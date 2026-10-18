<?php

namespace App\Http\Controllers;

use App\Domain\Identity\Enums\Role;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdministrationController extends Controller
{
    public function users(): View
    {
        return view('pages.admin.users', ['users' => User::orderBy('name')->get(), 'roles' => Role::cases()]);
    }

    public function storeUser(Request $request, AuditLogger $audit): RedirectResponse
    {
        $data = $request->validate(['name' => 'required|string|max:120', 'username' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9._-]+$/', 'unique:users,username'], 'email' => 'nullable|email|unique:users,email', 'role' => ['required', Rule::enum(Role::class)], 'password' => ['required', 'string', Password::defaults(), 'confirmed']]);
        $user = User::create([...$data, 'email' => $data['email'] ?: $data['username'].'@nfharapan.local', 'password' => Hash::make($data['password']), 'is_active' => true, 'force_password_change' => true]);
        $audit->record('user.created', $request->user(), $user, [], ['name' => $user->name, 'username' => $user->username, 'role' => $user->role->value]);

        return back()->with('status', 'Pengguna berhasil dibuat.');
    }

    public function updateUser(Request $request, User $user, AuditLogger $audit): RedirectResponse
    {
        $data = $request->validate(['role' => ['required', Rule::enum(Role::class)], 'is_active' => 'required|boolean']);
        if ($request->user()->is($user) && ($data['role'] !== $user->role->value || ! $data['is_active'])) {
            return back()->withErrors(['role' => 'Peran atau status akun sendiri tidak dapat diubah dari administrasi pengguna.']);
        }
        if ($user->isOwner() && ($data['role'] !== Role::Owner->value || ! $data['is_active']) && User::where('role', Role::Owner)->where('is_active', true)->count() <= 1) {
            return back()->withErrors(['role' => 'Pemilik aktif terakhir tidak dapat dinonaktifkan atau diturunkan perannya.']);
        }
        $before = $user->only(['role', 'is_active']);
        $user->update($data);
        if (! $user->is_active && config('session.driver') === 'database') {
            DB::table((string) config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
        }
        $audit->record('user.role_changed', $request->user(), $user, $before, $user->only(['role', 'is_active']));

        return back()->with('status', 'Akses pengguna diperbarui.');
    }

    public function resetPassword(Request $request, User $user, AuditLogger $audit): RedirectResponse
    {
        abort_if($request->user()->is($user), 422, 'Gunakan pengaturan keamanan untuk mengganti kata sandi akun sendiri.');
        $data = $request->validate(['password' => ['required', 'string', Password::defaults(), 'confirmed']]);
        $user->update(['password' => Hash::make($data['password']), 'force_password_change' => true]);
        if (config('session.driver') === 'database') {
            DB::table((string) config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
        }
        $audit->record('user.password_reset', $request->user(), $user);

        return back()->with('status', "Kata sandi sementara {$user->name} diperbarui.");
    }

    public function audit(Request $request): View
    {
        $filters = $request->validate(['event' => ['nullable', 'string', 'max:100'], 'actor_id' => ['nullable', 'integer', 'exists:users,id'], 'target' => ['nullable', 'string', 'max:50'], 'from' => ['nullable', 'date'], 'to' => ['nullable', 'date', 'after_or_equal:from']]);
        $logs = AuditLog::with('actor')
            ->when($filters['event'] ?? null, fn ($q, $event) => $q->where('event', 'like', '%'.addcslashes($event, '%_\\').'%'))
            ->when($filters['actor_id'] ?? null, fn ($q, $actorId) => $q->where('actor_id', $actorId))
            ->when($filters['target'] ?? null, fn ($q, $target) => $q->where('auditable_type', $target))
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->whereDate('occurred_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->whereDate('occurred_at', '<=', $to))
            ->latest('occurred_at')->paginate(30)->withQueryString();

        return view('pages.admin.audit', ['logs' => $logs, 'actors' => User::query()->whereHas('auditLogs')->orderBy('name')->get(), 'targets' => AuditLog::query()->whereNotNull('auditable_type')->distinct()->orderBy('auditable_type')->pluck('auditable_type')]);
    }
}
