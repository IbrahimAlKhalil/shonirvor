<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Verification extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'verification' => 'required|integer|exists:users,verification_token'
        ];
    }

    public function messages()
    {
        return [
            'verification.required' => 'ভেরিফিকেশন কোড দিতে হবে।',
            'verification.integer' => 'শুধুমাত্র নাম্বার দিতে পারবেন।',
            'verification.exists' => 'ভুল ভেরিফিকেশন কোড দিয়েছেন।'
        ];
    }
}
