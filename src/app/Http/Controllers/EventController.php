<?php

namespace App\Http\Controllers;

use App\{Event, Country, City, Category, EventHost, Photo, Ticket, User};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    protected $redirectTo = 'profile';

    public function index()
    {
        return view('pages.createEvent', [
            'countries' => Country::all(),
            'cities' => City::all(),
            'categories' => Category::all(),
        ]);
    }

    public function editPage($id)
    {
        return view('pages.editEvent', [
            'event' => Event::find($id),
            'countries' => Country::all(),
            'cities' => City::all(),
            'categories' => Category::all(),
        ]);
    }

    /**
     * Creates a new event.
     *
     * @param  int  $user_id
     * @param  Request request containing the description
     * @return Response
     */
    public function create(Request $request)
    {
        $event = DB::transaction(function () use ($request) {
            $event = new Event();
            $this->authorize('create', $event);
            $event->name = $request->input('name');
            $event->description = $request->input('description');
            $event->date = $request->input('date');
            $event->price = $request->input('price');
            $event->address = $request->input('address');
            $event->numberspots = $request->input('numberspots');
            $event->idcategory = $request->input('idCategory');
            $event->idlocation = $request->input('idLocation');
            $event->save();

            $picture = $request->file('cover');
            $extension = $picture->getClientOriginalExtension();
            $path = $event->id . '.' . $extension;
            Storage::disk('public')->put('/event/' . $path,  File::get($picture));
            $photo = new Photo();
            $photo->name = $path;
            $photo->idevent = $event->id;
            $photo->save();

            $event_host = new EventHost();
            $this->authorize('create', Auth::user());
            $event_host->iduser = Auth::user()->id;
            $event_host->idevent = $event->id;
            $event_host->save();

            return $event;
        });



        return redirect()->route('event.show', $event->id);
    }

    /**
     * Shows the event for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $event = Event::find($id);

        return view('pages.event', [
            'event' => $event,
        ]);
    }

    /**
     * Search results.
     *
     * @param  int  $id
     * @return Response
     */
    public function search(Request $request)
    {
        $events = Event::where('date', '>=', today())->orderBy('date')->get();
        $events1 = null;
        $events2 = null;
        $events3 = null;

        $str = $request->input('event');
        if ($str != null) {
            $events = Event::where(function ($query) use ($str) {
                $query->where('name', 'ilike', '%' . $str . '%')
                    ->orWhere('description', 'ilike', '%' . $str . '%');
            })->orderBy('date')->get();
        }

        $loc = $request->input('location');
        if ($loc != null) {
            $events1 = Event::where(function ($query) use ($loc) {
                $query->where('address', 'ilike', '%' . $loc . '%');
            })->orderBy('date')->get();

            $events2 = Event::whereHas('location', function ($query) use ($loc) {
                $query->where('name', 'ilike', '%' . $loc . '%');
            })->orwhereHas('location.country', function ($q) use ($loc) {
                $q->where('name', 'ilike', '%' . $loc . '%');
            })->get();

            $events1 = $events1->merge($events2);
        }

        $date = $request->input('date');
        $events3 = null;
        if ($date != null) {
            $events3 = Event::where(function ($query) use ($date) {
                $query->where("date", ">=", $date . ' 00:00:00')
                    ->where("date", "<=", $date . ' 23:59:59');
            })->orderBy('date')->get();
        }

        if ($events1 != null || $events3 != null) {
            if ($events1 != null)
                $events = $events->intersect($events1);
            if ($events3 != null)
                $events = $events->intersect($events3);
        }


        // $query = $request->input('event');
        // if ($query == null)
        //     $events = Event::all();
        // else
        //     $events = DB::table('event')
        //         ->whereRaw('tsv @@ to_tsquery(?)', $query)
        //         ->get();


        $categories = Category::all();
        return view('pages.searchEvent', ['events' => $events, 'categories' => $categories]);
    }


    /**
     * Delete the event for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
        $event = Event::find($id);

        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->route('home.show');
    }

    public function showForm()
    {
        return view('pages.createEvent');
    }

    /**
     * Updates the state of an individual item.
     *
     * @param  int  $id
     * @param  Request request containing the new state
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $event = Event::find($request->id);

        $this->authorize('update', $event);

        if ($request->input('address') != null)
            $event->address = $request->input('address');


        if ($request->input('name') != null)
            $event->name = $request->input('name');


        if ($request->input('description') != null)
            $event->description = $request->input('description');


        if ($request->input('date') != null)
            $event->date = $request->input('date');

        if ($request->input('price') != null)
            $event->price = $request->input('price');

        if ($request->input('idCategory') != null)
            $event->idcategory = $request->input('idCategory');

        if ($request->input('idLocation') != null)
            $event->idlocation = $request->input('idLocation');

        if ($request->input('numberspots') != null)
            $event->numberspots = $request->input('numberspots');

        $event->save();

        return redirect()->route('event.show', $event->id);
    }

    /**
     * Invite a user to join event
     */
    public function invite(Request $request, $id)
    {


        return redirect()->route('event.show', $id);
    }

    public function filter(Request $request)
    {
        $events_req = $request->input('events');
        $ids = array();

        foreach ($events_req as $event) {
            array_push($ids, $event['id']);
        }

        $events = Event::all()->whereIn('id', $ids);

        if ($request->has('category')) {
            error_log('to aqui');

            $events = $events->whereIn('idcategory', $request->input('category'));
        }

        $html = '';
        foreach ($events as $event) {
            $pr = $event->price . '€';
            if ($event->price == null) {
                        $pr = "FREE";
            }

            $html .= '
                <div class="card border-darkly mb-3" style="max-width: 40rem;">
            <a href="event/' . $event->id . '">
                <div class="card-body">
                    <h4 class="card-title">' . $event->name . '</h4>
                    <h6 class="card-subtitle mb-2 text-muted">' . $event->date . ' @ ' . $event->location->name . ', ' . $event->location->country->name . '</h6>
                    <p class="card-text">' . $event->description . '</p>
                    <p class="card-text">' . $pr . '</p>
                </div>
            </a>
        </div>
            ';
        }

        return response()->json(['html' => $html]);
    }
}
