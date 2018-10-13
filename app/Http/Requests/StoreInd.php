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
            /*'mobile' => 'required|digits:11',
            'referrer' => 'digits:11',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'facebook' => 'nullable|url',
            'age' => 'required|integer|min:10',
            'nid' => 'required|integer|unique:users',
            'district' => 'exists:districts,id',
            'thana' => 'exists:thanas,id',
            'union' => 'exists:unions,id',
            'village' => 'exists:villages,id',
            'thana-request' => 'required_with:no-thana,on',
            'union-request' => 'required_with:no-union,on',
            'village-request' => 'required_with:no-village,on',*/
            /*'address' => 'string',
            'images.*' => 'image',
            'experience-certificate' => 'image',
            'work-methods.*' => 'required|exists:work_methods,id'*/
        ];
    }
}
