<?php

namespace App\Http\Middleware;

use App\Http\Models\Fleet\FleetUser;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

/**
 * Посредник (Middleware) для проверки на необходимость обновления пароля пользователя GP BACKEND
 * @package App\Http\Middleware
 */
class AdminPasswordRenew
{
    /**
     * AdminPasswordRenew check
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
            if (time() >= strtotime(Auth::guard('admin')->user()->expired_at))
                return redirect()->route("admin.password.renew");
            return $next($request);
        }
    }
}
