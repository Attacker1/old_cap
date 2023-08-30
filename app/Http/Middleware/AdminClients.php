<?php

namespace App\Http\Middleware;

use Closure;
use \Illuminate\Support\Facades\Auth;


class AdminClients
{

    public function handle($request, Closure $next, $guard = "admin-clients")
    {
        $url = parse_url(url()->previous());
        $query = $url['query'] ??  '';
        parse_str($query,$params);
        if(array_key_exists('utm_source', $params)) {
            session()->forget('utms');
            session()->push('utms', $params);
        }

        if($request->getPathInfo()=='/feedback/new') {
             session()->push('redirectAfterLogin', route('admin-clients.feedback.index'));
        }
        if (!Auth::guard($guard)->check()) {
            return redirect()->route("admin-clients.auth.showlogin");
        }
        return $next($request);
    }

}
