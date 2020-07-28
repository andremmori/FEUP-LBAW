<?php

namespace App\Http\Controllers;

use App\{User};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EditProfileController extends Controller
{
    protected $redirectTo = 'profile';


    public function index($id)
    {
        $user = User::find($id);
        return view('pages.editProfile', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $this->authorize('update', $user);

        if($request->input('name') != null) {
            $this->validate(request(), ['name' => 'string|max:255',]);
            $user->name = $request->input('name');
        }

        if($request->input('address') != null) {
            $this->validate(request(), ['address' => 'string|email|max:255',]);
            $user->email = $request->input('address');
        }

        if($request->input('password') != null){
            $this->validate(request(), ['password' => 'string|min:8',]);
            $user->password = bcrypt($request->input('password'));
        }

        if($request->input('birthdate') != null)
            $user->birthdate = $request->input('birthdate');

        if($request->input('gender') != null)
            $user->gender = $request->input('gender');

        $user->save();

        return redirect()-> route('profile.show', $id);
    }
}
