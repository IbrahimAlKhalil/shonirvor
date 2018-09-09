<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIndSubCategory extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'sub-category' => 'required|unique:ind_sub_categories,category'
        ];
    }
}
