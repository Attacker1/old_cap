<?php

namespace App\Http\Requests\Common;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class PaymentsFromRequest
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
            'lead_id' => 'sometimes | required | string | max:36',
            'amount' => 'sometimes | required | integer ',
        ];
    }

    public function messages()
    {
        return [
            'lead_id.required' => 'Нет номера сделки!',
            'lead_id.max' => 'Номер сделки ограничен 36 символами!',
            'amount.required' => 'Ошибка в сумме оплаты',
            'amount.amount' => 'Сумма оплаты должна быть целочисленным значением',
        ];
    }
}