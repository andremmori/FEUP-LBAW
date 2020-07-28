<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;


class ContactController extends Controller
{
    /**
     * Return contact page
     */
    public function index(){
        return view('pages.contact');
    }

    public function send(Request $request)
    {
        Mail::to('meethologyadm@gmail.com')->send(new ContactMail($request->input("contact_email"), $request->input("subject"), $request->input("reason")));
        return view('pages.contact');
    }
}
