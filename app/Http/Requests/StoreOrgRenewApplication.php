<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreOrgRenewApplication extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $serviceIds = DB::table('orgs')
            ->where('user_id', auth()->id())
            ->pluck('id')->toArray();

        $packageIds = DB::table('packages')
            ->where('package_type_id', 2)
            ->pluck('id')->toArray();

        $paymentMethodIds = DB::table('payment_methods')
            ->pluck('id')->toArray();

        return [
            'service' => 'required|' . Rule::in($serviceIds),
            'package' => 'required|' . Rule::in($packageIds),
            'payment-method' => 'required|' . Rule::in($paymentMethodIds),
            'from' => 'required|numeric|digits_between:4,11',
            'transaction-id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'service.required' => 'সার্ভিস সিলেক্ট করুন',
            'service.in' => 'ভুল সার্ভিস সিলেক্ট করেছেন',

            'package.required' => 'প্যাকেজ সিলেক্ট করুন',
            'package.in' => 'ভুল প্যাকেজ সিলেক্ট করেছেন',

            'payment-method.required' => 'পেমেন্ট মেথড সিলেক্ট করুন',
            'payment-method.in' => 'ভুল পেমেন্ট মেথড সিলেক্ট করেছেন',

            'from.required' => 'যে নাম্বার থেকে টাকা পাঠানো হয়েছে তার কমপক্ষে শেষ ৪ ডিজিট দিন',
            'from.numeric' => 'শুধু ইংরেজি নাম্বার দিন',
            'from.digits_between' => 'কমপক্ষে শেষ ৪ ডিজিট এবং সর্বোচ্চ ১১ ডিজিট দেওয়া যাবে',

            'transaction-id.required' => 'টাকা পেমেন্ট করে Transaction ID দিন'
        ];
    }
}
