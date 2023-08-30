<?php

namespace App\Http\Controllers\Admin;

use App\Basics;
use App\Http\Classes\Message;
use App\Http\Models\Admin\AdminUser;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;


class AdminAuthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function index()
    {
        return view('admin.login.login');
    }


    public function authenticate(Request $request)
    {
        $rules = [
            "email" => "required",
            "password" => "required",
        ];

        $validator = Validator::make($request->all(), $rules, $this->messages());

        if ($validator->fails()) {
            toastr()->error('Ошибка авторизации','Ошибка');
            return redirect()->back()->withErrors($validator);
        }


        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            toastr()->error(Message::login()['locked'],'Ошибка');
            return redirect()->back();
        }
        if ($auth = Auth::guard('admin')->attempt(
            [
                'email' => $request->input("email"),
                'password' => $request->input("password")
            ],1
        )) {
            return redirect()->route('admin.main.index');
        } else {
            $this->incrementLoginAttempts($request);
            toastr()->error(Message::login()['error'],'Ошибка');
            return \redirect()->route('admin.login');
        }
    }

    public function messages()
    {
        return [
            'email.required' => '',
            'password.required' => '',

        ];
    }

    public function logout()
    {
        session_start();
        Auth::guard('admin')->logout();
        session_unset();
        session_destroy();
        return redirect()->route('admin.login');
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), 5, 60*10
        );
    }


}
