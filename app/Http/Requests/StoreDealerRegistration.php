<?php

namespace App\Http\Requests;

use App\Models\PendingDealer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreDealerRegistration extends FormRequest
{
    public function authorize()
    {
        return !PendingDealer::where('user_id', Auth::id())->first();
    }

    public function rules()
    {
        return [
            'email' => 'email',
            'nid' => 'required|integer|unique:users',
            'age' => 'required|integer|min:10',
            'address' => 'required|string',
            'photo' => 'image',
            'documents.*' => 'image'
        ];
    }
}
