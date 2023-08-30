<?php

namespace App\Http\Requests\AdminClient\Auth;

use \Illuminate\Foundation\Http\FormRequest;
use App\Http\Classes\Common;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Rules\CaptchaVerifiedRule;
use App\Http\Rules\SmsCodeVerifiedRule;
use App\Http\Rules\ThrottleRule;

class LoginSmsFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {

        return [
            'phone' => 'required|string|regex:/[0-9_]+/i|max:11|min:11|exists:clients,phone',
            //'sms_code' => [new SmsCodeVerifiedRule(), new ThrottleRule('submission', $maxAttempts = 5, $decayInMinutes = 10), 'required', 'string','regex:/[0-9]+/i', 'max:5', 'min:5'],
            'sms_code' => [new SmsCodeVerifiedRule(), new ThrottleRule('submission', $maxAttempts = 5, $decayInMinutes = 10), 'required', 'string', 'max:12', 'min:5'],
            'token' => ['required', new CaptchaVerifiedRule]
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Заполните, пожалуйста, поле',
            'phone.numeric' => 'Ошибка ввода для номера телефона',
            'phone.exists' => 'Телефон не найден',
            'sms_code.required' => 'Заполните, пожалуйста, поле',
            'sms_code.numeric' => 'Ошибка ввода смс-пароля',
            'sms_code.max' => 'Ошибка ввода смс-пароля',
            'sms_code.min' => 'Ошибка ввода смс-пароля',
            'sms_code.exists' => 'Ошибка ввода смс-пароля',
            'token.required' => 'Произошла ошибка. Обратитесь к администратору'
        ];
    }

    protected function prepareForValidation()
    {
        $input = array_map('trim',$this->all());
    
        if(isset($input['phone'])) {
            $input['phone'] = Common::format_phone($input['phone']);
            $this->replace($input);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors])
        );
    }
}