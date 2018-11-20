<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetSendCode extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mobile' => 'required|digits:11|exists:users'
        ];
    }

    public function messages()
    {
        return [
            'mobile.required' => 'মোবাইল নাম্বার দিতে হবে।',
            'mobile.digits' => 'মোবাইল নাম্বার অবশ্যই ১১ সংখ্যার হতে হবে।',
            'mobile.exists' => 'আপনি ভুল মোবাইল নাম্বার দিয়েছেন। এই নাম্বারে কোন একাউন্ট নেই।'
        ];
    }
}
