<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Класс-посредник (Middleware) для проверки авторизации admin пользователей
 * @package App\Http\Middleware
 */
class Admin
{
    /**
     * Admin auth check
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = "admin")
    {
        
        if (!Auth::guard('admin')->check())
            return redirect()->route("admin.login");
        else {
            return $next($request);
        }
    }
}
