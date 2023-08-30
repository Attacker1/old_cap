<?php

namespace App\Http\Requests\Catalog;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CategoryFormRequest extends FormRequest
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
            'name' => 'sometimes | required | string | max:255',
            'slug' => 'sometimes | required | string | unique:categories,slug,' . $this->id,
            'visible' => 'sometimes | required | integer',
            'parent_id' => 'sometimes | required | integer',
        ];
    }

}
