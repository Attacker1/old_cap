<?php

namespace App\Http\Requests\Catalog;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CategoriesTranslatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('admin')->user()->hasPermission('catalog-manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'ms_name' => 'sometimes | required | string | max:255',
            'cap_name' => 'nullable | string | max:255',
        ];
    }

}
