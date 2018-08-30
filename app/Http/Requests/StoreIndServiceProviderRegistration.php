<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIndServiceProviderRegistration extends FormRequest
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
            'email' => 'email|unique:ind_service_provider_infos',
            'nid' => 'required|integer|unique:users',
            'age' => 'required|integer|min:10',
            'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'photos.*' => 'image',
            'documents.*' => 'image'
        ];
    }
}
