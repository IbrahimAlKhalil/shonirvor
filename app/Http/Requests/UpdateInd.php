<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateInd extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $id = $this->route('ind_id');

        return [
            'mobile' => 'required|digits:11|unique:inds,mobile,' . $id,
            'email' => 'email|unique:inds,email,' . $id,
            'personal-email' => 'email|unique:users,email,' . Auth::id(),
            'nid' => 'required|integer|unique:users,nid,' . Auth::id(),
            'age' => 'required|integer|min:10',
            'district' => 'exists:districts,id',
            'thana' => 'exists:thanas,id',
            'union' => 'exists:unions,id',
            'category' => 'exists:categories,id',
            'sub-categories.*' => 'exists:sub_categories,id',
            'address' => 'string',
            'website' => 'url',
            'facebook' => 'url',
            'photo' => 'image',
            'images.*' => 'image',
            'work-methods.*' => 'required|exists:work_methods,id'
        ];
    }
}
