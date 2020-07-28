<?php

namespace App\Policies;

use App\Invite;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;


class InvitePolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        // Any user can invite
        return Auth::check();
    }
}
