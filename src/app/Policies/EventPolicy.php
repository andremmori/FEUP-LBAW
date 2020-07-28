<?php

namespace App\Policies;

use App\User;
use App\Event;
use App\EventHost;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventPolicy
{
    use HandlesAuthorization;

    public function show(User $user)
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
        // Any user can create a new event
        return Auth::check();
    }

    public function delete(User $user, Event $event)
    {
        // Only an event owner can delete it
        return in_array($user, array($event->hosts)) || $user->admin;
    }

    public function update(User $user, Event $event)
    {
        // Only an event owner can delete it
        
        
        return DB::table('eventhost')->where('idevent', '=', $event->id)->where('iduser', '=', Auth::user()->id)->exists() || $user->admin;
    }
}
