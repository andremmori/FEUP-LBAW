<?php

namespace App\Http\Controllers;

use App\{Report, User, Event, Comment};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        return view('pages.event');
    }

    /**
     * Creates a new report.
     *
     * @param  int  $user_id
     * @param  Request request containing the description
     * @return Response
     */

    public function reportEvent(Request $request, $id)
    {
        if($request->input('description') != null){
            $user = Auth::user();
            
            $report = DB::transaction(function () use ($request, $user, $id)
            {
                $report = new Report();
                $report->description = $request->input('description');
                $report->iduser = $user->id;
                $report->idevent = $id;
                $report->idcomment = null;
                $report->date = now()->toDateTimeString();
                
                $report->save();

                return $report;
            });
        }

        return redirect()->route('event.show', $id);
    }

    public function reportComment(Request $request, $id)
    {
        if($request->input('description') != null){
            $user = Auth::user();
            
            $report = DB::transaction(function () use ($request, $user)
            {
                $report = new Report();
                $report->description = $request->input('description');
                $report->iduser = $user->id;
                $report->idevent = null;
                $report->idcomment = $request->input('commentId');
                $report->date = now()->toDateTimeString();
                
                $report->save();

                return $report;
            });
        }

        return redirect()->route('event.show', $id);
    }
}
