<?php

namespace App\Http\Controllers;

use App\{Event};

class PageController extends Controller
{

    /**
     * Shows the homepage.
     *
     * @param  int  $id
     * @return Response
     */
    public function home()
    {

        return view('pages.home', [
            "event" => Event::all(),
            "sports" => Event::all()->where('idcategory', 1)->take(3),
            "music" => Event::all()->where('idcategory', 2)->take(3),
            "film" => Event::all()->where('idcategory', 6)->take(3),
            "social" => Event::all()->where('idcategory', 9)->take(3),
            "learning" => Event::all()->where('idcategory', 10)->take(3),
        ]);
    }

    /**
     * Shows the FAQ page.
     *
     * @param  int  $id
     * @return Response
     */
    public function faq()
    {

        return view('pages.faq');
    }

    /**
     * Shows the About page.
     *
     * @param  int  $id
     * @return Response
     */
    public function about()
    {

        return view('pages.about');
    }
}
