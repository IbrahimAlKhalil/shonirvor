<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDealer extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'required|string',
            'mobile' => 'required|unique:users',
            'email' => 'email|unique:users',
            'nid' => 'required|integer|unique:users',
            'age' => 'integer|min:10',
            'qualification' => 'string',
            'address' => 'string',
            'password' => 'required',
            'photo' => 'image',
            'documents.*' => 'image'
        ];
    }


    public function attributes()
    {
        return [
          'documents.*' => 'documents'
        ];
    }
}
