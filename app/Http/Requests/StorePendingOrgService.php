<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendingOrgService extends FormRequest
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
            'org-name' => 'required',
            'description' => 'required',
            'mobile' => 'required|digits:11|unique:org_services|unique:pending_org_services',
            'email' => 'email|unique:org_services|unique:pending_org_services',
            'personal-email' => 'email|unique:users,email',
            'address' => 'required|string',
            'nid' => 'required|integer|unique:users',
            'age' => 'required|integer|min:10',
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
            'docs.*' => 'image'
        ];
    }
}
