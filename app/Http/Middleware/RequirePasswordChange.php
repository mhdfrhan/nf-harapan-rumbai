<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $allowed = $request->routeIs('security.edit', 'password.confirm', 'password.confirmation', 'logout')
            || $request->is('livewire/*');

        if ($user?->force_password_change && ! $allowed) {
            return redirect()->route('security.edit')->with('status', 'Ganti kata sandi sementara sebelum menggunakan sistem.');
        }

        return $next($request);
    }
}
