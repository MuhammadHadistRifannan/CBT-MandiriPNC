<?php

namespace App\Http\Middleware;

use App\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // belum login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // role sesuai → lanjut
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // redirect sesuai role user
        return match ($user->role) {

            'admin' =>
            redirect()->route('admin.dashboard'),

            'pengawas' =>
            redirect()->route('pengawas.dashboard'),

            default =>
            redirect()->route('dashboard'),
        };
    }
}
