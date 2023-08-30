<?php

namespace App\Http\Requests\Admin\Clients;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Classes\Client;

class UpdateClientFormRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        $client = new Client($this->all());
        return $client->rules['edit'];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */

    public function messages()
    {
        $client = new Client($this->all());
        return $client->messages['edit'];
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
     * Prepare the data for validation.
     *
     * @return void
     */

    protected function prepareForValidation()
    {
        $client = new Client($this->all());
        $client->prepareForValidation();

        $this->merge([
            'name' => $client->name,
            'second_name' => $client->second_name,
            'phone' => $client->phone,
            'email' => $client->email,
        ]);
    }
}