<?php

namespace App\Http\Controllers\Api;

use App\Http\Models\Admin\AdminApiUser;
use App\Http\Models\Admin\AdminUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * Класс авторизации партнера (Fleet) по API
 * Class FleetApiAuthController
 * @package App\Http\Controllers\Api
 */
class AdminApiAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Сформированный токен
     * @var string
     */
    private $apiToken;

    /**
     * Количество попыток авторизации
     * @var int
     */
    protected $maxAttempts = 5;

    /**
     * Формирует уникальный токен длиной 60, на основании функций random, base64 и uniqid
     *
     * FleetApiAuthController constructor.
     */
    public function __construct()
    {
        // Unique Token
        $this->apiToken = Str::uuid();
    }

    /**
     * Перенаправление на главную страницу ? Может будет редирект на описание
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return \redirect()->route('admin.login');
    }

    /**
     * Авторизиция с валидацией входных параметров, принимаемые параметры @login, @password
     * - Выполняется поиск пользователя с заданным логином и статусом
     * в случае успеха обновляются данные пользователя, @token и @created_at
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse / result, token,expired_at
     */
    public function authenticate(Request $request)
    {

        $rules = [
            "login" => "required",
            "password" => "required",
        ];

        $validator = Validator::make($request->all(), $rules, $this->messages());

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => 'Invalid Api auth'
            ], 401);
        }


        if ($this->hasTooManyLoginAttempts($request)) {
            return response()->json([
                'result' => false,
                'message' => 'Превышено количество попыток авторизации'
            ], 401);
        }


        if (Auth::guard('admin_api')->attempt(
            [
                'login' => $request->input("login"),
                'password' => $request->input("password"),

            ]
        )) {

            // Update Token
            $postArray = [
                'token' => $this->apiToken,
                'created_at' => Carbon::now(),
                'expired_at' => Carbon::now()->addHours(2),
            ];

            AdminApiUser::where('login', $request->login)->update($postArray);

            return response()->json([
                'result' => true,
                'bearer_token' => $this->apiToken,
                'expired_at' => 7200,
            ], 200);
        } else {

            $this->incrementLoginAttempts($request);
            return response()->json([
                'result' => false,
                'message' => '#Ошибка авторизации ',
            ], 401);
        }

    }


    /**
     * Опиисание требуемых полей, в случае ошибки валидации
     * @return array
     */
    public function messages()
    {
        return [
            'login.required' => '',
            'password.required' => '',

        ];
    }

    /**
     * @deprecated
     * Функция logout api пользователя
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logout()
    {

        Auth::guard('admin_api')->user()->logout();

        return response()->json([
            'result' => true,
            'message' => 'logout complete'
        ], 200);

    }

    /**
     * Количество возможных попыток авторизации пользователя (5 раз), с задержкой в 60 минут возможности авторизоваться, при превышении
     * @param Request $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxAttempts, 60*1
        );
    }


}
