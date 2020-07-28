<?php

namespace App\Http\Controllers;

use App\Invite;
use App\Event;
use Illuminate\Support\Facades\Mail;
use App\Mail\InviteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    /**
     * Creates and sends invite
     */

     public function send(Request $request, $id){
         
         $invite = new Invite();

        // $this->authorize('create');

        // Create invite
         $invite->idevent = $id;
         $email = $request->input('email');
         $invite->iduser = Auth::user()->id;
         $invite->email = $email;
         $invite->save();


        // Send email
         Mail::to($email)->send(new InviteMail($email, Auth::user(), Event::find($id)));

        return redirect()->route('event.show', $id);
     }
}
