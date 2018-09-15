<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInd extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mobile' => 'required|digits:11|unique:inds',
            'email' => 'email|unique:inds',
            'personal-email' => 'email|unique:users,email',
            'nid' => 'required|integer|unique:users',
            'age' => 'required|integer|min:10',
            'district' => 'exists:districts,id',
            'thana' => 'exists:thanas,id',
            'union' => 'exists:unions,id',
            'category' => 'required',
            'address' => 'string',
            'website' => 'url',
            'facebook' => 'url',
            'photo' => 'image',
            'images.*' => 'image',
            'experience-certificate' => 'image',
            'work-methods.*' => 'required|exists:work_methods,id'
        ];
    }
}
