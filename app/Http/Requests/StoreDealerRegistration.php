<?php

namespace App\Http\Requests;

use App\Models\PendingDealer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreDealerRegistration extends FormRequest
{
    public function authorize()
    {
        return !PendingDealer::where('user_id', Auth::id())->first() && !Auth::user()->hasRole('dealer');
    }

    public function rules()
    {
        return [
//            'mobile' => 'digits:11',
            'email' => 'email',
            'age' => 'integer|min:10',
            'nid' => 'integer|unique:users',
            'address' => 'string',
            'photo' => 'image',
            'documents.*' => 'image'
        ];
    }
}
