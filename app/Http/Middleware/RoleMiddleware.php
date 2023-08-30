<?php

namespace App\Http\Middleware;
use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * @param $request
     * @param Closure $next
     * @param $role
     * @param null $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission = null)
    {

        if(auth()->guard('admin')->user()->hasRole($role)) {
            return $next($request);
        }
        if($permission !== null && auth()->guard('admin')->user()->can($permission)) {
            return $next($request);
        }

        abort(404);
    }
}
