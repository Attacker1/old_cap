<?php

namespace App\Http\Middleware;

use App\Http\Models\Fleet\FleetUser;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;


class AdminLoginCheck
{
    /**
     * Fleet auth check
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = "admin")
    {

        if (Auth::guard('admin')->check())
            return redirect()->route("admin.main.index");
        else
            return $next($request);
    }
}
