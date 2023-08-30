<?php

namespace App\Http\Controllers\Auth;

use App\Http\Classes\Acl;
use App\Http\Controllers\Controller;
use App\Http\Models\Admin\AdminUser as User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;




class AuthController extends Controller
{

    public function login($message = false){

        if (!Auth::guard('admin')->user()) {
            if (!empty($message))
                toastr()->error($message);

            return view('admin.login.login', [
                'title'
            ]);
        }
        else {
            return \redirect()->route('backend.index');
        }
    }

    /**
     * Redirect the user to the Okta authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {

        return redirect('/saml2/okta/login');
    }


    public function handleProviderCallback($user)
    {


        $localUser = User::where('login', $user->getAttributes()['email'])->first();
        $password = uniqid();

        if (! $localUser) {
            return false;
            $this->login('Пользователя нет в БД');

        } else {

            $localUser->password = bcrypt($password);
            $localUser->updated_at = Carbon::now();
            $localUser->save();

        }

        try {

            $auth = Auth::guard('admin')->attempt(
                [
                    'login' => $localUser->login,
                    'password' => $password
                ],1
            );

        } catch (\Throwable $e) {
            toastr()->error('Пользователя нет');
            $this->login('Ошибка авторизации');
        }

        return true;


        return redirect()->route('backend.index');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/403');
    }

}

