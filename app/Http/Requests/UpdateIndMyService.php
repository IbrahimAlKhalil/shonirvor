<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateIndMyService extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user = Auth::user();
        return [
            'mobile' => 'required|digits:11',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'facebook' => 'nullable|url',
            'division' => 'required|exists:divisions,id',
            'district' => 'required|exists:districts,id',
            'thana' => 'required',
            'union' => 'required',
            'village' => 'required',
            'address' => 'required|string',
            'slug' => Rule::unique('inds')->ignore(request('service')),
            'sub-categories.*.id' => 'exists:sub_categories,id',
            'sub-category-reqeusts.*.name' => 'required|min:3',
            // TODO: Subcategory
//            'sub-categories.*.work-methods.*.rate' => 'nullable|integer',
            'work-images.*.description' => 'string|min:10|nullable',
            'work-images.*.file' => 'nullable|image'
        ];
    }

    public function withValidator($validator)
    {
//        dd($this->request);
    }

    public function messages()
    {
        return [
            'mobile.required' => 'মোবাইল নাম্বার দিতে হবে',
            'mobile.digits' => 'মোবাইল নাম্বার ১১টি সংখ্যার হতে হবে',
            'email.email' => 'ইমেইলে ভুল আছে, দয়া করে চেক করুন',
            'website.url' => 'ওয়েবসাইটের লিঙ্কে ভুল আছে, দয়া করে চেক করুন',
            'facebook.url' => 'ফেসবুকের লিঙ্কে ভুল আছে, দয়া করে চেক করুন',
            'address.required' => 'ঠিকানা দিতে হবে',
            'slug.unique' => 'দুঃখিত! এই লিংকটি কেউ নিয়ে নিয়েছে ।'
        ];
    }
}
