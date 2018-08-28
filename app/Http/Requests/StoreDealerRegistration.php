<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDealerRegistration extends FormRequest
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
            'name' => 'required|string',
            'mobile' => 'required|unique:dealer_registrations',
            'email' => 'email|unique:dealer_registrations',
            'nid' => 'required|integer|unique:dealer_registrations',
            'password' => 'required',
            'age' => 'required|integer|min:10',
            'address' => 'required|string',
            'photo' => 'image'
        ];
    }
}
