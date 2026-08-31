<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (strtolower($role) === 'admin') {
            if ($user && strtolower((string) $user->role?->name) === 'admin') {
                return $next($request);
            }

            // Allow Admin Panel access when impersonating user in another tab
            if (session()->has('admin_session_user_id')) {
                $adminUser = User::find(session('admin_session_user_id'));
                if ($adminUser && strtolower((string) $adminUser->role?->name) === 'admin') {
                    return $next($request);
                }
            }

            abort(403, 'Unauthorized admin access.');
        }

        abort_unless(strtolower((string) $user?->role?->name) === strtolower($role), 403);

        return $next($request);
    }
}