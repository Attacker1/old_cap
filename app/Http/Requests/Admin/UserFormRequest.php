<?php

namespace App\Http\Requests\Admin;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('admin')->user()->hasPermission('manage-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name' => 'sometimes | required | string | max:255',
            'amo_name' => 'nullable | string | max:255',
            'email' => 'sometimes | required | email | max:255 ',
            'password' => 'sometimes | nullable | string | max:255',
            'role_id' => 'sometimes | required | integer',
            'disabled' => 'sometimes | required | integer',
        ];
    }

}
