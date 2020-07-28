<?php

namespace App\Policies;

use App\User;
use App\Event;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PhotoPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Event $event)
    {
        return true;
    }

    public function list(User $user)
    {
        // Any user can list its own events
        return Auth::check();
    }

    public function create(User $user)
    {
        return Auth::check();
    }

    public function delete(User $user, Event $event)
    {
        // Only a card owner can delete it
        return $user->id == $event->user_id;
    }
}
