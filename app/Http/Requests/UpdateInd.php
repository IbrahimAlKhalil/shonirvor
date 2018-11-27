<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Package;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateInd extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {

        $rules = [
            'mobile' => 'required|digits:11',
            'referrer' => 'digits:11|different:mobile|exists:users,mobile|nullable',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'facebook' => 'nullable|url',
            'division' => 'required|exists:divisions,id',
            'district' => 'required|exists:districts,id',
            'thana' => 'required_without:no-thana',
            'union' => 'required_without:no-union',
            'village' => 'required_without:no-village',
            'thana-request' => 'required_with:no-thana',
            'union-request' => 'required_with:no-union',
            'village-request' => 'required_with:no-village',
            'address' => 'required|string',
            'category' => 'required_without:no-category',
            'category-request' => 'required_with:no-category',
            'sub-categories.*' => 'exists:sub_categories,id',
            'sub-category-requests.*.name' => 'required_with:no-sub-category',
            'images.*.description' => 'string|min:10|nullable',
            // TODO: Review image size
            'images.*.file' => 'image|max:800',
            'experience-certificate' => 'image|max:800',
            // TODO: Review pdf size
            'cv' => 'mimes:pdf|max:1024',
            'package' => 'required|exists:packages,id',
            'from' => 'required_with:transactionId',
            'payment-method' => 'required_with:transactionId'
        ];

        $user = Auth::user();
        $first = !$user->inds()->onlyApproved()->exists() || !$user->orgs()->onlyApproved()->exists();
        addValidationRules($rules, [
            'nid' => [$first, 'required|unique:users,nid,' . $user->id],
            'month' => [$first, 'required|between:1,12'],
            'year' => [$first, 'required|max:' . (string)(Date('Y') - 18)],
            'day' => [$first, 'required|between:1,31'],
            'identities.*' => [$first, 'required|image']
        ]);

        return $rules;
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('thana', 'exists:thanas,id', function ($data) {
            return !is_null($data->thana);
        });
        $validator->sometimes('union', 'exists:unions,id', function ($data) {
            return !is_null($data->union);
        });
        $validator->sometimes('village', 'exists:villages,id', function ($data) {
            return !is_null($data->village);
        });

        $serviceCategoryIds = array_map(function ($item) {
            return $item['category_id'];
        }, Auth::user()->inds()->select('category_id')->get()->toArray());

        $categoryIds = array_map(function ($item) {
            return $item['id'];
        }, Category::onlyInd()->onlyConfirmed()->select('id')->get()->toArray());

        $validator->sometimes('category', ['bail', Rule::notIn($serviceCategoryIds), Rule::in($categoryIds)], function ($data) {
            return $data->category;
        });

        $indPackageIds = Package::onlyInd()->pluck('id')->toArray();
        if (!in_array($this->post('package'), $indPackageIds)) {
            $validator->failed();
        }
    }

    public function messages()
    {
        return [
            'mobile.required' => 'মোবাইল নাম্বার দিতে হবে',
            'mobile.digits' => 'মোবাইল নাম্বার ১১টি সংখ্যার হতে হবে',
            'referrer.digits' => 'রেফারার মোবাইল নাম্বার ১১টি সংখ্যার হতে হবে',
            'referrer.different' => 'নিজের মোবাইল নাম্বার এবং রেফারারের মোবাইল নাম্বার এক হওয়া যাবেনা',
            'referrer.exists' => 'এই নাম্বারে কোন একাউন্ট পাওয়া যায়নি',
            'email.email' => 'ইমেইলে ভুল আছে, দয়া করে চেক করুন',
            'website.url' => 'ওয়েবসাইটের লিঙ্কে ভুল আছে, দয়া করে চেক করুন',
            'facebook.url' => 'ফেসবুকের লিঙ্কে ভুল আছে, দয়া করে চেক করুন',
            'year.required' => 'বছর দিতে হবে',
            'day.required' => 'দিন দিতে হবে',
            'month.required' => 'মাস দিতে হবে',
            'nid.required' => 'জাতীয় পরিচয়পত্রের নাম্বার দিতে হবে',
            'thana-request.required_with' => 'থানার নাম দিতে হবে',
            'union-request.required_with' => 'ইউনিয়নের নাম দিতে হবে',
            'village-request.required_with' => 'গ্রামের নাম দিতে হবে',
            'category-request.required_with' => 'ক্যাটাগরির নাম দিতে হবে',
            'address.required' => 'ঠিকানা দিতে হবে'
        ];
    }
}
