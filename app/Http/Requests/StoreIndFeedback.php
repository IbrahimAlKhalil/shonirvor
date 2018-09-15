<?php

namespace App\Http\Requests;

use App\Models\IndService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreIndFeedback extends FormRequest
{
    public function authorize()
    {
        $provider = IndService::find($this->post('feedbackable_id'));
        $authId = Auth::user()->getAuthIdentifier();

        return !$provider->feedbacks()->where('user_id', $authId)->exists() && $authId != $provider->user->id;
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
