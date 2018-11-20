<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordReset extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|integer|exists:users,reset_token',
            'password' => 'required|string|min:6|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'SMS কোড দিতে হবে।',
            'code.integer' => 'শুধুমাত্র নাম্বার দিতে পারবেন।',
            'code.exists' => 'ভুল SMS কোড দিয়েছেন।',
            'password.required' => 'পাসওয়ার্ড দিতে হবে।',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
            'password.confirmed' => 'পাসওয়ার্ড মিলে নাই।'
        ];
    }
}
