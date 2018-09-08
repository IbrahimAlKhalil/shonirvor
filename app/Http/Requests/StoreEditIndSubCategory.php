<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEditIndSubCategory extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'edit-sub-category' => 'required|unique:ind_sub_categories,category'
        ];
    }
}
