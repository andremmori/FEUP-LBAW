<?php

namespace App\Policies;

use App\{User, Event};

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    public function show(User $user, User $usr)
    {
        return $user->id == $usr->id || $user->admin;
    }

    public function list(User $user)
    {
        // Any user can list its own events
        return Auth::check();
    }

    public function create(User $user)
    {
        // Any user can create a new event
        return Auth::check();
    }

    public function delete(User $user, User $usr)
    {
        return $user->id == $usr->id || $user->admin;
    }

    public function update(User $user, User $usr)
    {
        return $user->id == $usr->id || $user->admin;
    }
}
