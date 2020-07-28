<?php

namespace App\Http\Controllers;

use App\{Event, Ticket};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected $redirectTo = 'profile';

    public function index()
    {
        return view('pages.createEvent');
    }

    /**
     * Creates a new ticket.
     *
     * @param  Request request containing the description
     * @return Response
     */
    public function create(Request $request)
    {
        $ticket = DB::transaction(function() use ($request){
            $ticket = new Ticket();
            $this->authorize('create', $ticket);

            $ticket->iduser = $request->input('idUser');
            $ticket->idevent = $request->input('idEvent');

            $ticket->save();
            return $ticket;
        });

        return redirect()->route('event.show', $ticket->idevent);
    }

    /**
     * Delete a ticket.
     *
     * @param  Request request containing the description
     * @return Response
     */
    public function delete(Request $request)
    {
        $ticket = Ticket::where('idevent', '=', $request->idEvent)->where('iduser', '=', $request->idUser);
        $ticket->delete();
        return redirect()->route('event.show', $request->idEvent);
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

        $this->authorize('show', $event);

        return view('pages.event', ['event' => $event]);
    }
}
