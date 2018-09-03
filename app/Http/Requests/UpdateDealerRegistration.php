<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateDealerRegistration extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $nidID = Auth::user()->pendingDealer->id;

        return [
            'mobile' => 'digits:11',
            'email' => 'email',
            'age' => 'integer|min:10',
            'nid' => 'integer|unique:users,nid,'. $nidID,
            'address' => 'string',
            'photo' => 'image',
            'documents.*' => 'image'
        ];
    }
}
