<?php

namespace App\Http\Requests;

use App\Models\Package;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UpdateOrg extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'name' => 'required|min:3',
            'mobile' => 'required|digits:11',
            'referrer' => 'nullable|digits:11|different:mobile|exists:users,mobile',
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
            'sub-categories.*.id' => 'exists:sub_categories,id',
            'sub-category-requests.*.name' => 'required_with:no-sub-category',
            'images.*.description' => 'string|min:10|nullable',
            'images.*.file' => 'image',
            'identities.*' => 'required|image',
            'package' => 'required',
            'from' => 'required_with:transactionId',
            'payment-method' => 'required_with:transactionId'
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('division', 'exists:divisions,id', function ($data) {
            return !is_null($data->division);
        });
        $validator->sometimes('district', 'exists:districts,id', function ($data) {
            return !is_null($data->district);
        });
        $validator->sometimes('thana', 'exists:thanas,id', function ($data) {
            return !is_null($data->thana);
        });
        $validator->sometimes('union', 'exists:unions,id', function ($data) {
            return !is_null($data->union);
        });
        $validator->sometimes('village', 'exists:villages,id', function ($data) {
            return !is_null($data->village);
        });
        $validator->sometimes('category', 'exists:categories,id', function ($data) {
            return !is_null($data->category);
        });

        $user = Auth::user();
        $first = !$user->inds()->onlyApproved()->exists() || !$user->orgs()->onlyApproved()->exists();
        $validator->sometimes('nid', 'required|integer|unique:users,nid,' . $user->id, function () use (&$first) {
            return $first;
        });


        $orgPackageIds = Package::onlyOrg()->pluck('id')->toArray();
        if (!in_array($this->post('package'), $orgPackageIds)) {
            $validator->failed();
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'প্রতিষ্ঠানের নাম দিতে হবে',
            'mobile.required' => 'মোবাইল নাম্বার দিতে হবে',
            'mobile.digits' => 'মোবাইল নাম্বার ১১টি সংখ্যার হতে হবে',
            'referrer.digits' => 'রেফারার মোবাইল নাম্বার ১১টি সংখ্যার হতে হবে',
            'referrer.different' => 'নিজের মোবাইল নাম্বার এবং রেফারারের মোবাইল নাম্বার এক হওয়া যাবেনা',
            'referrer.exists' => 'এই নাম্বারে কোন একাউন্ট পাওয়া যায়নি',
            'email.email' => 'ইমেইলে ভুল আছে, দয়া করে চেক করুন',
            'website.url' => 'ওয়েবসাইটের লিঙ্কে ভুল আছে, দয়া করে চেক করুন',
            'facebook.url' => 'ফেসবুকের লিঙ্কে ভুল আছে, দয়া করে চেক করুন',
            'nid.required' => 'জাতীয় পরিচয়পত্রের নাম্বার দিতে হবে',
            'address.required' => 'ঠিকানা দিতে হবে',
            'thana-request.required_with' => 'থানার নাম দিতে হবে',
            'union-request.required_with' => 'ইউনিয়নের নাম দিতে হবে',
            'village-request.required_with' => 'গ্রামের নাম দিতে হবে',
            'category-request.required_with' => 'ক্যাটাগরির নাম দিতে হবে'
        ];
    }
}
