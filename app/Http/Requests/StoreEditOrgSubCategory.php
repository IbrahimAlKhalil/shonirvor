<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEditOrgSubCategory extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'edit-sub-category' => 'required|unique:org_sub_categories,category'
        ];
    }
}
