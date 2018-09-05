<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $id = $this->route('org_service_registration');

        return [
            'org-name' => 'required',
            'description' => 'required',
            'mobile' => 'required|digits:11|unique:org_services|unique:pending_org_services,mobile,' . $id,
            'email' => 'email|unique:org_services|unique:pending_org_services,email,' . $id,
            'latitude' => 'required',
            'longitude' => 'required',
            'images.*' => 'image',
            'docs.*' => 'image'
        ];
    }
}
