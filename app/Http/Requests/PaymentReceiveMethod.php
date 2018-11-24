<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentReceiveMethod extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|string|in:bkash,rocket',
            'number' => 'required|digits_between:11,12'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'একাউন্টের ধরন নির্বাচন করতে হবে।',
            'number.required' => 'মোবাইল নাম্বার দিতে হবে।',
            'number.digits_between' => 'মোবাইল নাম্বার অবশ্যই ১১-১২ সংখ্যার হতে হবে।'
        ];
    }
}
