<?php

namespace App\Http\Requests\AdminClient\Auth;

use \Illuminate\Foundation\Http\FormRequest;
use App\Http\Classes\Common;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Rules\CaptchaVerifiedRule;

class GetSmsFormRequest extends FormRequest
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
            'token' => ['required', new CaptchaVerifiedRule]
            ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Ошибка ввода для номера телефона',
            'phone.numeric' => 'Ошибка ввода для номера телефона',
            'phone.exists' => 'Телефон не найден',
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

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors])
        );
    }
}