<?php

namespace App\Http\Controllers;

use App\Mail\RecoverPasswordMail;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Shows the user for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::find($id);

        $this->authorize('show', $user);

        return view('pages.profile', ['user' => $user]);
    }

    /**
     * Add profile picture
     */
    public function profilePic(Request $request)
    {

        $user = Auth::user();
        $picture = $request->file('profilepic');
        $extension = $picture->getClientOriginalExtension();
        $path = $user->id . '.' . $extension;
        Storage::disk('public')->put('/profile/' . $path,  File::get($picture));
        $user->profilepicture = $path;
        $user->save();

        return redirect()->route('profile.show', $user->id);
    }

    /**
     * Deletes the user for a given id.
     *
     * @param  Request
     * @return Response
     */
    public function delete(Request $request)
    {
        $user = User::find($request->input('userId'));

        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('home.show');;
    }

    /**
     * Password recover form
     */
    public function recoverform()
    {
        return view('auth.recoverForm');
    }

    /**
     * Password reset form
     */
    public function resetform($id)
    {
        return view('auth.reset', ['id' => $id]);
    }

    /**
     * Sends reset password mail
     * @param Request
     */
    public function recover(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', '=', $email)->first();
        if ($user != null) {
            Mail::to($email)->send(new RecoverPasswordMail($user));
            session()->flash('success');
        } else {
            session()->flash('error');
        }

        return redirect()->route('password.recover.form');
    }

    /**
     * Resets password
     * @param Request
     */
    public function reset(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $user = User::find($id);

        $user->password = bcrypt($request->input('password'));

        $user->save();



        return redirect()->route('home.show');
    }
}
