<?php

namespace App\Http\Requests;

use App\Models\OrgService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrgFeedback extends FormRequest
{
    public function authorize()
    {
        $provider = OrgService::find($this->post('feedbackable_id'));
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
