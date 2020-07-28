<?php

namespace App\Http\Controllers;

use App\{Event, Photo};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{

    /**
     * Creates a new event.
     *
     * @param  int  $user_id
     * @param  Request request containing the description
     * @return Response
     */
    public function create(Request $request)
    {

        $eventId = $request->input('eventid');
        $event = Event::find($eventId);
        $picture = $request->file('eventpic');
        $extension = $picture->getClientOriginalExtension();
        $path = $eventId . '_' . count($event->photos) . '.' . $extension;
        Storage::disk('public')->put('/event/' . $path,  File::get($picture));
        
        $photo = new Photo();
        $photo->name = $path;
        $photo->idevent = $eventId;
        $photo->save();
        $this->authorize('create', $photo);

        return redirect()->route('event.show', $eventId);
    }

}
