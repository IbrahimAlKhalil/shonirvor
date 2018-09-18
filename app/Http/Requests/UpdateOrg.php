<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateOrg extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('org_id');

        return [
            'name' => 'required',
            'description' => 'required',
            'mobile' => 'required|digits:11|unique:orgs,mobile,' . $id,
            'email' => 'email|unique:orgs,email,' . $id,
            'personal-email' => 'email|unique:users,email,' . Auth::id(),
            'address' => 'required|string',
            'nid' => 'required|integer|unique:users,nid,' . Auth::id(),
            'website' => 'url',
            'facebook' => 'url',
            'no_area' => 'boolean',
            'district' => 'exists:districts,id',
            'thana' => 'exists:thanas,id',
            'union' => 'exists:unions,id',
            'logo' => 'image',
            'photo' => 'image',
            'images.*' => 'image',
        ];
    }
}
