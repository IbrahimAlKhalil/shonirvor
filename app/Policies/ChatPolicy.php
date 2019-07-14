<?php

namespace App\Policies;

use App\Models\ConversationMember;
use App\Models\Ind;
use App\Models\Org;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatPolicy
{
    use HandlesAuthorization;

    public function getConversations(User $user, Request $request)
    {
        return $this->authorize($user, $request->input('id'), $request->input('type'));
    }

    public function createConversation(User $user, Request $request)
    {
        $type = $request->input('type');
        $target = $request->input('target');
        $targetType = $request->input('targetType');

        // Authorize
        if (!$this->authorize($user, $request->input('id'), $request->input('type'))) {
            return false;
        }

        // Individual and organization can open conversation with only admin

        $targetUserAdmin = null;

        if ($type === 'ind' || $type === 'org') {
            $targetUserAdmin = User::find($target)->hasRole('admin');
            return $targetType === 'user' && $targetUserAdmin;
        }

        // Normal user can't open conversation with normal user
        if ($type === 'user' && !Auth::user()->hasRole('admin')) {
            if ($targetUserAdmin == null) {
                $targetUserAdmin = User::find($target)->hasRole('admin');
            }

            return $targetUserAdmin;
        }

        // Admin can open conversation with anyone

        return Auth::user()->hasRole('admin');
    }

    public function getMessages(User $user, Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        // Authorize

        if (!$this->authorize($user, $id, $type)) {
            return false;
        }

        return ConversationMember::query()
            ->where('memberable_type', $type)
            ->where('memberable_id', $id)
            ->where('conversation_id', $request->input('cid'))
            ->exists();
    }

    public function authorize(User $user, $id, $type)
    {
        $models = [
            'ind' => Ind::class,
            'org' => Org::class
        ];

        if ($type === 'org' || $type === 'ind') {
            return $models[$type]::onlyApproved()->where('id', $id)->where('user_id', $user->id)->exists();
        }

        return $user->id == $id;
    }
}
