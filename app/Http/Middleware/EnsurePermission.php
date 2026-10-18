<?php

namespace App\Http\Middleware;

use App\Services\AuditLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! $request->user()?->hasPermission($permission)) {
            app(AuditLogger::class)->record('authorization.denied', $request->user(), null, [], [], ['permission' => $permission, 'path' => $request->path()]);
        }
        abort_unless($request->user()?->hasPermission($permission), 403);

        return $next($request);
    }
}
