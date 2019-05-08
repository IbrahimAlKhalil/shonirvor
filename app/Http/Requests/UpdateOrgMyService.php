<?php

namespace App\Http\Requests;

use App\Models\Slug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateOrgMyService extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('service');
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
            'slug' => [
                Rule::unique('slugs', 'name')->ignore(Slug::where('sluggable_type', 'org')->where('sluggable_id', $id)->select('id')->first()->id),
                'required',
                'regex:/^[A-Za-z0-9]+(?:[_\-\.]*)?(?:\w+)$/',
                'min:5',
                'max:191'
            ],
            'sub-categories.*.id' => 'exists:sub_categories,id',
            // TODO: Subcategory
//            'sub-categories.*.work-methods.*.rate' => 'nullable|integer',
            'work-images.*.description' => 'string|min:10|nullable',
            // TODO: Review Image size
            'new-work-images.*.file' => 'nullable|image|max:15000',
            'work-images.*.file' => 'nullable|image|max:15000',
            'cover-photo' => 'nullable|image|max:15000',
            'logo' => 'nullable|image|max:15000',
            // TODO: validation
            'additional-prices.*.id' => 'exists:org_additional_prices,id'
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
            'slug.required' => 'সার্ভিস লিঙ্ক দিতে হবে',
            'slug.unique' => 'এই লিঙ্কটি অন্য কেউ ব্যাবহার করছে',
            'slug.regex' => 'লিঙ্ক ফরমেটটি সঠিক নয়',
            'slug.min' => 'লিঙ্ক ৫ অক্ষরের কম হতে পারবে না',
            'slug.max' => 'লিঙ্ক ১৯১ অক্ষরের বেশি হতে পারবে না'
        ];
    }
}
