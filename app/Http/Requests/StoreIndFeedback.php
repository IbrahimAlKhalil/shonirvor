<?php

namespace App\Http\Requests;

use App\Models\Ind;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreIndFeedback extends FormRequest
{
    public function authorize()
    {
        $provider = Ind::find($this->post('feedbackable_id'));
        $authId = Auth::user()->getAuthIdentifier();

        return !$provider->feedbacks()->where('user_id', $authId)->exists() && $authId != $provider->user->id;
    }

    public function rules()
    {
        return [
            'star' => 'required',
            'say' => 'string|nullable',
            'feedbackable_id' => 'required|exists:inds,id'
        ];
    }
}
