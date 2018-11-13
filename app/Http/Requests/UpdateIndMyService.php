<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'day' => 'required|between:1,31',
            'month' => 'required|between:1,12',
            'year' => 'required|max:' . (string)(Date('Y') - 18),
            'nid' => 'required|integer|unique:users,nid,' . $user->id,
            'division' => 'required|exists:divisions,id',
            'district' => 'required|exists:districts,id',
            'thana' => 'required',
            'union' => 'required',
            'village' => 'required',
            'address' => 'required|string',
            'sub-categories.*.id' => 'exists:sub_categories,id',
//            'sub-categories.*.work-methods.*.rate' => 'nullable|integer',
            'work-images.*.description' => 'string|min:10|nullable',
            'work-images.*.file' => 'nullable|image',
            'identities.*' => 'nullable|image',
            'experience-certificate' => 'nullable|image',
            'cv' => 'mimes:pdf'
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
            'year.required' => 'বছর দিতে হবে',
            'day.required' => 'দিন দিতে হবে',
            'month.required' => 'মাস দিতে হবে',
            'nid.required' => 'জাতীয় পরিচয়পত্রের নাম্বার দিতে হবে',
            'address.required' => 'ঠিকানা দিতে হবে'
        ];
    }
}
