<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreAdApplication extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $packageIds = DB::table('packages')
            ->where('package_type_id', 6)
            ->pluck('id')->toArray();

        $paymentMethodIds = DB::table('payment_methods')
            ->pluck('id')->toArray();

        return [
            'package' => 'required|' . Rule::in($packageIds),
            'payment-method' => 'required|' . Rule::in($paymentMethodIds),
            'from' => 'required|numeric|digits_between:4,11',
            'transaction-id' => 'required',
            'url' => 'nullable|url'
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('image', 'nullable|image||max:800', function () {
            return request()->method == 'PUT';
        });

        $validator->sometimes('image', 'required|image|max:800', function () {
            return request()->method == 'POST';
        });
    }

    public function messages()
    {
        return [

            'package.required' => 'প্যাকেজ সিলেক্ট করুন',
            'package.in' => 'ভুল প্যাকেজ সিলেক্ট করেছেন',

            'payment-method.required' => 'পেমেন্ট মেথড সিলেক্ট করুন',
            'payment-method.in' => 'ভুল পেমেন্ট মেথড সিলেক্ট করেছেন',

            'from.required' => 'যে নাম্বার থেকে টাকা পাঠানো হয়েছে তার কমপক্ষে শেষ ৪ ডিজিট দিন',
            'from.numeric' => 'শুধু ইংরেজি নাম্বার দিন',
            'from.digits_between' => 'কমপক্ষে শেষ ৪ ডিজিট এবং সর্বোচ্চ ১১ ডিজিট দেওয়া যাবে',

            'transaction-id.required' => 'টাকা পেমেন্ট করে Transaction ID দিন',

            'image.required' => 'বিজ্ঞাপনের জন্য ছবি দিতে হবে',

            'url.url' => 'লিঙ্কটি সঠিক নয়',
            'image.max' => 'বিজ্ঞাপনের ছবি :max কিলোবাইটের বেশি হতে পারবে না'
        ];
    }
}
