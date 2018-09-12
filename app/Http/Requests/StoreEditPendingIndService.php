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
            'mobile' => 'required|digits:11|unique:ind_services|unique:pending_ind_services,mobile,' . $id,
            'email' => 'email|unique:ind_services,email|unique:pending_ind_services,email,' . $id,
            'personal-email' => 'email|unique:users,email,' . Auth::id(),
            'nid' => 'required|integer|unique:users,nid,' . Auth::id(),
            'age' => 'required|integer|min:10',
            'district' => 'exists:districts,id',
            'thana' => 'exists:thanas,id',
            'union' => 'exists:unions,id',
            'category' => 'exists:ind_categories,id',
            'sub-categories.*' => 'exists:ind_sub_categories,id',
            'address' => 'string',
            'website' => 'url',
            'facebook' => 'url',
            'photo' => 'image',
            'images.*' => 'image',
            'docs.*' => 'image',
            'work-methods.*' => 'required|exists:work_methods,id'
        ];
    }
}
