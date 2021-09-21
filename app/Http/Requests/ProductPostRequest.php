<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductPostRequest extends FormRequest
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
            'name' => 'required|unique:products|min:3', // minimum of 3 characters
            'price' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Product name is required',
            'name.unique' => 'Product name already exists.',
            'name.min' => 'Product name must be at least 3 characters long',
            'price.required' => 'Product price is required',
            'price.numeric' => 'Price must be a numeric value',
        ];
    }
}
