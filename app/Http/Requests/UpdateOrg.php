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
        $user = Auth::user();

        return [
            'name' => 'required|min:3',
            'mobile' => 'required|digits:11',
            'referrer' => 'nullabel|digits:11|different:mobile',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'facebook' => 'nullable|url',
            'nid' => 'required|integer|unique:users,nid,' . $user->id,
            'division' => 'required',
            'district' => 'required|exists:districts,id',
            'thana' => 'required_without:no-thana|exists:thanas,id',
            'union' => 'required_without:no-union|exists:unions,id',
            'village' => 'required_without:no-village|exists:villages,id',
            'thana-request' => 'required_with:no-thana',
            'union-request' => 'required_with:no-union',
            'village-request' => 'required_with:no-village',
            'address' => 'string',
            'category' => 'required_without:no-category|exists:categories,id',
            'category-request' => 'required_with:no-category',
            'sub-categories.*.id' => 'exists:sub_categories,id',
            'sub-category-requests.*.name' => 'required_with:no-sub-category',
            'images.*.description' => 'string|min:10|nullable',
            'images.*.file' => 'image',
            'identities.*' => 'required|image',
            'trade-license' => 'required|image',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'প্রতিষ্ঠানের নাম দিতে হবে',
            'mobile.required' => 'মোবাইল নাম্বার দিতে হবে',
            'mobile.digits' => 'মোবাইল নাম্বার ১১টি সংখ্যার হতে হবে',
            'referrer.digits' => 'রেফারার মোবাইল নাম্বার ১১টি সংখ্যার হতে হবে',
            'referrer.different' => 'নিজের মোবাইল নাম্বার এবং রেফারারের মোবাইল নাম্বার এক হওয়া যাবেনা',
            'email.email' => 'ইমেইলে ভুল আছে, দয়া করে চেক করুন',
            'website.url' => 'ওয়েবসাইটের লিঙ্কে ভুল আছে, দয়া করে চেক করুন',
            'facebook.url' => 'ফেসবুকের লিঙ্কে ভুল আছে, দয়া করে চেক করুন',
            'nid.required' => 'জাতীয় পরিচয়পত্রের নাম্বার দিতে হবে'
        ];
    }
}
