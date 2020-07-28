<?php

namespace App\Http\Controllers;

use App\{User, Event, Comment};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    protected $redirectTo = 'comment';

    public function index()
    {
        return view('pages.event');
    }

    public function show($id)
    {
        $comment = Comment::find($id);

        return redirect()->route('event.show', $comment->idevent);
    }

    public function create(Request $request, $id)
    {

        if($request->input('comment') != null){
            $user = Auth::user();
            
            $comment = DB::transaction(function () use ($request, $user, $id)
            {
                $comment = new Comment();
                $comment->text = $request->input('comment');
                $comment->iduser = $user->id;
                $comment->idevent = $id;
                $comment->date = now()->toDateTimeString();
                
                $comment->save();

                return $comment;
            });
        }

        return redirect()->route('event.show', $id);
    }

    public function delete($id)
    {
        $comment = Comment::find($id);

        $comment->delete();

        return redirect()->route('admin.reports');
    }
}