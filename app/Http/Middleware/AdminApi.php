<?php

namespace App\Http\Middleware;

use App\Http\Models\Admin\AdminApiUser;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;


/**
 * Посредник (Middleware), Проверка API пользователя на авторизацию и доступ к разделам API.
 * Проверка Bearer Token из header в принимаемых заголовках
 * @package App\Http\Middleware
 */
class AdminApi
{
    /**
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */

    public function handle($request, Closure $next, $guard = "admin-api")
    {

        if (!self::check($request)) {
            return response()->json([
                'result' => false,
                'message' => 'Invalid token/Token expired!'
            ], 401);
        }
        else {
            return $next($request);
        }
    }

    /**
     * Auth api check
     *
     * @param $request
     * @return bool
     */
    protected function check($request){

        if(request()->bearerToken()){
            if(AdminApiUser::where('token',request()->bearerToken())->where('expired_at','>=', now())->count()) {
                return true;
            }
            return false;
        }
        return false;
    }
}
