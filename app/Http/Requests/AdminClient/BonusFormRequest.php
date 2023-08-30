<?php

namespace App\Http\Requests\AdminClient;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class BonusFormRequest extends FormRequest
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
            'client_id' => 'required | string | unique:bonuses,client_id,' . $this->id,
            'points' => 'required | integer | max:255',
            'promocode' => 'required | string | max:255 | unique:bonuses,promocode,' . $this->id,
        ];
    }

}
