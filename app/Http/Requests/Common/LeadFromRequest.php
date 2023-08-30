<?php

namespace App\Http\Requests\Common;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class LeadFromRequest
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
            'stylist_id' => 'nullable | integer ',
            'amo_lead_id' => 'nullable | integer ',
            'state_id' => 'nullable | integer ',
            'description' => 'nullable | string ',
            'deadline_at' => 'nullable | date_format:Y-m-d',
            'summ' => 'nullable | integer ',
        ];
    }

    public function messages()
    {
        return [
            'stylist_id.required' => 'Нет стилиста!',
            'amo_lead_id.required' => 'номера сделки',
        ];
    }
}