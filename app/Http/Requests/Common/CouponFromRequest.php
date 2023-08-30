<?php

namespace App\Http\Requests\Common;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CouponFromRequest  extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name' => 'sometimes | required | string | max:100 ',
            'type' => 'sometimes | required | string | max:100',
            'price' => 'sometimes | required | integer ',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Имя должно быть заполнено!',
            'name.max' => 'Имя ограничено 100 символами!',
            'name.unique' => 'Купон с таким наименованием уже использован!',
            'type.required' => 'Ошибка в поле тип купона',
            'price.required' => 'Ошибка в сумме купона',
        ];
    }
}