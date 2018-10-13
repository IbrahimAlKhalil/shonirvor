<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrg extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'mobile' => 'required|digits:11|unique:orgs',
            'email' => 'email|unique:orgs',
            'personal-email' => 'email|unique:users,email',
            'address' => 'required|string',
            'nid' => 'required|integer|unique:users',
            'website' => 'url',
            'facebook' => 'url',
            'no_area' => 'boolean',
            'district' => 'exists:districts,id',
            'thana' => 'exists:thanas,id',
            'union' => 'exists:unions,id',
            'category' => 'required',
            'logo' => 'required|image',
            'photo' => 'image',
            'images.*' => 'image',
        ];
    }
}
