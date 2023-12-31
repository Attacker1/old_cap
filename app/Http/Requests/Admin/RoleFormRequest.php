<?php

namespace App\Http\Requests\Admin;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class RoleFormRequest extends FormRequest
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
            'name' => 'sometimes | required | string | max:255',
            'slug' => 'sometimes | required | string | max:255 | unique:roles,slug,' . $this->id,
            'permissions' => 'sometimes | required',
        ];
    }

}
