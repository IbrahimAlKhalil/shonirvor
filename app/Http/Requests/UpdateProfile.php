<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfile extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'mobile' => 'required|unique:users,mobile,' . Auth::id(),
            'password' => 'confirmed',
            'old-password' => 'nullable|required_with:password|old_password:users,' . Auth::id(),
            'photo' => 'nullable|image|max:15000'
        ];
    }

    public function messages()
    {
        return [
            'password.confirmed' => 'পাসওয়ার্ড মিলেনি',
            'mobile.unique' => 'মোবাইল নাম্বারটি ইতোমধ্যেই নেয়া হয়েছে',
            'mobile.required' => 'মোবাইল নাম্বার দিতে হবে',
            'old-password.required_with' => 'পুরাতন পাসওয়ার্ড দিতে হবে',
            'old-password.old_password' => 'পাসওয়ার্ড ভুল হয়েছে।',
            'photo.max' => 'প্রোফাইল পিকচার ৮০০ কিলোবাইটের বেশি হতে পারবেনা'
        ];
    }

    public function withValidator($validator)
    {

    }
}
