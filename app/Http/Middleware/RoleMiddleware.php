<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param mixed ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Нет доступа: пользователь не авторизован');
        }

        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!$user->hasAnyRole($roles)) {
            abort(403, 'Нет доступа: недостаточно прав');
        }

        if (!$user->hasRole($roles)) {
            abort(403, 'Доступ запрещен');
        }

        return $next($request);
    }
}
