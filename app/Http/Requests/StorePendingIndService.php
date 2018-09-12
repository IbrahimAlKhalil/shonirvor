<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendingIndService extends FormRequest
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
            'mobile' => 'required|digits:11|unique:ind_services|unique:pending_ind_services',
            'email' => 'email|unique:ind_services|unique:pending_ind_services',
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
            'docs.*' => 'image',
            'work-methods.*' => 'required|exists:work_methods,id'
        ];
    }
}
