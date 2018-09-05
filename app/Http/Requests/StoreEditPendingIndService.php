<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreEditPendingIndService extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = Auth::user()->pendingIndService->id;

        return [
            'name' => 'required',
            'mobile' => 'required|digits:11|unique:ind_services|unique:pending_ind_services,mobile,' . $id,
            'email' => 'email|unique:ind_services,email|unique:pending_ind_services,email,' . $id,
            'personal-email' => 'email|unique:users,email,' . Auth::id(),
            'nid' => 'required|integer|unique:users,nid,' . Auth::id(),
            'age' => 'required|integer|min:10',
            'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'images.*' => 'image',
            'docs.*' => 'image'
        ];
    }
}
