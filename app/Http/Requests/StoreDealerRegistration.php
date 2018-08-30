<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDealerRegistration extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'email|unique:dealer_registrations',
            'nid' => 'required|integer|unique:users',
            'age' => 'required|integer|min:10',
            'address' => 'required|string',
            'photo' => 'image',
            'documents.*' => 'image'
        ];
    }
}
