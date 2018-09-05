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
            'name' => 'required',
            'mobile' => 'required|digits:11|unique:ind_services|unique:pending_ind_services',
            'email' => 'email|unique:ind_services|unique:pending_ind_services',
            'personal-email' => 'email|unique:users,email',
            'nid' => 'required|integer|unique:users',
            'age' => 'required|integer|min:10',
            'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'image',
            'images.*' => 'image',
            'docs.*' => 'image'
        ];
    }
}
