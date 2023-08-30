<?php

namespace App\Http\Rules;

use Ixudra\Curl\Facades\Curl;
use Mockery\Exception;

class CaptchaVerifiedRule implements \Illuminate\Contracts\Validation\Rule
{

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        try {
            $response = Curl::to('https://www.google.com/recaptcha/api/siteverify')
                ->withConnectTimeout(90)
                ->withTimeout(90)
                ->withData([
                    'secret' => config('config.CAPTCHA_SECRET_KEY'),
                    'response' => $value
                ])
                ->get();    
        
            $response_arr = json_decode($response, true);    
            if($response) return $response_arr['success'];
        }
        catch (Exception $e){
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return 'Произошла ошибка. Обратитесь к администратору';
    }
}