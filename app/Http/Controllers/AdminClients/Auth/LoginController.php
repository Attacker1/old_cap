<?php

namespace App\Http\Controllers\AdminClients\Auth;

use App\Http\Classes\Message;
use App\Http\Classes\SMSRU;
use App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\AdminClient\Auth\LoginSmsFormRequest;
use App\Http\Requests\AdminClient\Auth\GetSmsFormRequest;
use App\Http\Classes\Common;
use Ixudra\Curl\Facades\Curl;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    protected $maxAttempts = 3; // Default is 5
    protected $decayMinutes = 5; // Default is 1

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest.admin-clients', ['except' => 'logout']);
    }
    
    public function showLoginForm()
    {

        $url = parse_url(url()->previous());
        $query = $url['query'] ??  '';
        parse_str($query,$params);
        if(array_key_exists('utm_source', $params)) {
            session()->forget('utms');
            session()->push('utms', $params);
        }

        /*dump(Hash::make($str));*/
        return view('admin-clients.auth.login', ['title' => 'Авторизация']);
    }

    public function username()
    {
        return 'login';
    }

    public function impersonate($token){

        $client = Client::whereAuthToken($token)->first();
        $this->guard()->login($client);
        return redirect()->route("admin-clients.repeat-order");
    }

    protected function guard()
    {
        return Auth::guard('admin-clients');
    }

    public function loginSms(LoginSmsFormRequest $request)
    {

        $phone = Common::format_phone($request->input('phone'));
        $client = Client::where('phone', $request->phone)->first();
        $this->guard()->login($client);
        
        if(session()->has('redirectAfterLogin')) {
            $redirect_to = session()->get('redirectAfterLogin', '');
            $res['redirect_to'] = $redirect_to[0];
            session()->forget('redirectAfterLogin');
        } else $res['redirect_to'] = route("admin-clients.orders.list");

        return response()->json($res); 
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route('admin-clients.auth.showlogin');
    }

    public function getSms(GetSmsFormRequest $request)
    {

        $sms_ru = new SMSRU(config('config.SMSRU_API_KEY'));

        $code = mt_rand(10000, 99999);
        //$code = 55445;
        $phone = Common::format_phone($request->input('phone'));

        session()->forget('sms_code');
        session()->push('sms_code', ['code'=>$code, 'time'=>time()]);

        try {
            $response = Curl::to('https://sms.ru/sms/send?api_id='.config('config.SMSRU_API_KEY').'&to='.$phone.'&msg='.$code.'&json=1')
                ->withConnectTimeout(90)
                ->withTimeout(90)
                ->get();    
        
            $response_arr = json_decode($response, true);

            if($response_arr['status']=='OK') $res['result'] = true;
            else $res['errors']['sms_send_res'] = 'Ошибка отправки смс';
            
            return response()->json($res);
        }

        catch (Exception $e){
            $res['result'] = false;
            return response()->json($res);
        }

        /*
        string(247) "{
            "status": "OK",
            "status_code": 100,
            "sms": {
                "79263279853": {
                    "status": "OK",
                    "status_code": 100,
                    "sms_id": "202116-1000000",
                    "cost": "0.00"
                }
            },
    "balance": 10
}"
        */

        /*$post = (object) [
            "to" => trim(request()->input('phone')),
            "msg" => trim($code),
            "from" => config('config.APP_NAME'),
        ];
        $post->test= 0;
        if ($sms_ru->send_one($post))
            return response()->json([
                "result" => true,
                "msg" => "Сообщение отправлено"
            ]);

        return response()->json([
            "result" => false,
            "msg" => "Сообщение не было отправлено!"
        ]);*/
        $res['result'] = true;
        return response()->json($res);
    }

}