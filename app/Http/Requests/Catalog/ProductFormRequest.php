<?php

namespace App\Http\Requests\Catalog;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Models\Catalog\Product;

class ProductFormRequest extends FormRequest
{

    public function __construct(Product $product)
    {

    }

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
            '_token' => 'sometimes | required | string | max:255',
            'name' => 'sometimes | required | string | max:255',
            'category_id' => 'sometimes | required | integer',
            'brand_id' => 'sometimes | required | integer',
            'content' => 'sometimes | nullable | string ',
            'slug' => 'sometimes | nullable | string | unique:products,slug,' . $this->id,
            'visible' => 'sometimes | nullable | integer',
            'attachment_id' => 'sometimes | nullable | string | max:255',
        ];
    }
}
