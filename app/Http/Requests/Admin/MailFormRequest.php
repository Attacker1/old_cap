<?php

namespace App\Http\Requests\Admin;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class MailFormRequest extends FormRequest
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
            'name' => 'sometimes | required | string | max:255 | unique:mail_templates,name,' . $this->id,
            'description' => 'nullable | string',
            'params' => ' nullable | string ',
            'html' => 'nullable | string ',
            'text' => 'nullable | string ',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Имя - обязательное поле',
            'name.max' => 'Имя - не более 255 символов',
            'slug.unique' => 'Системное имя (Slug) уже используется'
        ];
    }

}
