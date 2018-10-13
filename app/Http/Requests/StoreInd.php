<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreInd extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user = Auth::user();
        return [
            'mobile' => 'required|digits:11',
            'referrer' => 'digits:11|different:mobile',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'facebook' => 'nullable|url',
            'age' => ($user->age ? '' : 'required|') . 'integer|min:10',
            'nid' => ($user->nid ? '' : 'required|') . 'integer|unique:users,nid',
            'division' => 'required',
            'district' => 'required|exists:districts,id',
            'thana' => 'required_without:no-thana|exists:thanas,id',
            'union' => 'required_without:no-union|exists:unions,id',
            'village' => 'required_without:no-village|exists:villages,id',
            'thana-request' => 'required_with:no-thana',
            'union-request' => 'required_with:no-union',
            'village-request' => 'required_with:no-village',
            'address' => 'string',
            'category' => 'required_without:no-category|exists:categories,id',
            'category-request' => 'required_with:no-category',
            'sub-categories.*' => 'required_without:no-sub-category|exists:sub_categories,id',
            'sub-category-requests.*.name' => 'required_with:no-sub-category',
            'images.*.description' => 'string|min:10|nullable',
            'images.*.file' => 'image',
            'identities.*' => 'required|image',
            'experience-certificate' => 'image',
            'cv' => 'image'
        ];
    }
}
