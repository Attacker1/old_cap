<?php

namespace App\Http\Requests\Catalog;

use Illuminate\Foundation\Http\FormRequest;

class ColorFormRequest extends FormRequest
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
            'name' => 'required | string | max:255 |  unique:colors_refs,name,' . $this->id,
            'hex' => 'nullable | string | max:40 |  unique:colors_refs,hex,' . $this->id,
            'value' => 'nullable | string | max:7 '
        ];
    }
}
