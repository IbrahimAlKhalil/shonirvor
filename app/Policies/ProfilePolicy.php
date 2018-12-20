<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function update(User $user)
    {
        return $user->id == request('profile')->id;
    }
}
