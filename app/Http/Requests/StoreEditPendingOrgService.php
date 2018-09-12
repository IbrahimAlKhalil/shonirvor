<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEditPendingOrgService extends FormRequest
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
        $id = $this->route('org_id');

        return [
            'org-name' => 'required',
            'description' => 'required',
            'mobile' => 'required|digits:11|unique:org_services|unique:pending_org_services,mobile,' . $id,
            'email' => 'email|unique:org_services|unique:pending_org_services,email,' . $id,
            'personal-email' => 'email|unique:users,email,' . Auth::id(),
            'address' => 'required|string',
            'nid' => 'required|integer|unique:users,nid,' . Auth::id(),
            'age' => 'required|integer|min:10',
            'website' => 'url',
            'facebook' => 'url',
            'no_area' => 'boolean',
            'district' => 'exists:districts,id',
            'thana' => 'exists:thanas,id',
            'union' => 'exists:unions,id',
            'category' => 'required',
            'logo' => 'image',
            'photo' => 'image',
            'images.*' => 'image',
            'docs.*' => 'image'
        ];
    }
}
