<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrgServiceRegistration extends FormRequest
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
            'name' => 'required',
            'org-name' => 'required',
            'description' => 'required',
            'mobile' => 'required|digits:11|unique:org_services|unique:pending_org_services',
            'email' => 'email|unique:org_services|unique:pending_org_services',
            'personal-email' => 'email|unique:users,email',
            'address' => 'required|string',
            'nid' => 'required|integer|unique:users',
            'age' => 'required|integer|min:10',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image',
            'images.*' => 'image',
            'docs.*' => 'image'
        ];
    }
}
