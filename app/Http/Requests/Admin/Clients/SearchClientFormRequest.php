<?php

namespace App\Http\Requests\Admin\Clients;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Classes\Client;

class SearchClientFormRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        $client = new Client($this->all());
        return $client->rules['search'];
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
            'search_text' => $client->search_text
        ]);
    }
}