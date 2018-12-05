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
            'type' => 'required|in:ind,org',
            'star' => 'required',
            'feedbackable_id' => 'required|exists:' . request()->post('type') . 's,id'
        ];
    }
}
