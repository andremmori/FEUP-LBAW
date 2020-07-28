<?php

namespace App\Http\Controllers;

use App\{Event, User, Report, Comment, Promotion};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;


class AdminController extends Controller
{
    /**
     * Shows the admin page.
     *
     * @param  int  $id
     * @return Response
     */
    public function show()
    {
        $this->authorize('show', Auth::user());

        return view('pages.admin', ['admin' => Auth::user(),
                                    'users' => User::all(),
                                    'events' => Event::all()->sortBy('id'),
                                    ]);
    }

    /**
     * Shows the admin reports page.
     *
     * @param  int  $id
     * @return Response
     */
    public function reports()
    {
        $this->authorize('show', Auth::user());

        return view('pages.reports', [
            'admin' => Auth::user(),
            'event_reports' => Report::all()->where('idcomment', '', null)->sortBy('idevent'),
            'comment_reports' => Report::all()->where('idevent', '', null)->sortBy('idcomment'),
        ]);
    }

     /**
     * Shows the admin promotions page.
     *
     * @param  int  $id
     * @return Response
     */
    public function promotions()
    {
        $this->authorize('show', Auth::user());  

        return view('pages.promotions', [
            'admin' => Auth::user(),
            'promotions' => Promotion::all(),
            'users' => User::all(),
        ]);
    }

     /**
     * Shows the admin bans page.
     *
     * @param  int  $id
     * @return Response
     */
    public function bans()
    {
        $this->authorize('show', Auth::user());  

        return view('pages.bans', [
            'admin' => Auth::user(),
            'users' => User::all(),
        ]);
    }

    public function promote($id)
    {
        $user = Auth::user();

        $prommote = DB::transaction(function () use ($id, $user)
        {
            $promote = new Promotion();
            $promote->promoted = true;
            $promote->date = now()->toDateTimeString();
            $promote->idcreator = $user->id;
            $promote->idreceiver = $id;

            $promote->save();

            return $promote;
        });

        $receiver = User::find($id);
        $receiver->admin = TRUE;
        $receiver->save();

        return redirect()->route('admin.promotions');
    }

    public function demote($id)
    {
        $user = Auth::user();

        $demote = DB::transaction(function () use ($id, $user)
        {
            $demote = new Promotion();
            $demote->promoted = false;
            $demote->date = now()->toDateTimeString();
            $demote->idcreator = $user->id;
            $demote->idreceiver = $id;

            $demote->save();

            return $demote;
        });

        $receiver = User::find($id);
        $receiver->admin = FALSE;
        $receiver->save();

        return redirect()->route('admin.promotions');
    }

    public function ban($id)
    {

        $user = User::find($id);
        $user->banned = TRUE;
        $user->save();

        return redirect()->route('admin.bans');
    }

    public function unban($id)
    {

        $user = User::find($id);
        $user->banned = FALSE;
        $user->save();

        return redirect()->route('admin.bans');
    }

    
}
