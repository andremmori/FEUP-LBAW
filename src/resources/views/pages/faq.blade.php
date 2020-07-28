@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 3%; padding-bottom: 3%;">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm">
                <h1>FAQ</h1>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-9">
                <p>Here are the most Frequently Asked Questions about our platform. Click in any of the questions below to see our answer. 
                    If you have any other doubt feel free to ask us at our Contact Us page <a href="{{route('contact.index')}}">here</a>.
                </p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <p>
                    <a class="btn btn-primary" data-toggle="collapse" href="#faq1" role="button"
                        aria-expanded="false" aria-controls="faq1">How to search for an
                        event?</a>
                </p>
                <div class="collapse multi-collapse" id="faq1">
                    <div class="card card-body">
                        In the main page, you write down the informartion you wish to look for on the Name, Location and
                        Date
                        fields
                        , and click on the search button.
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <p>
                    <a class="btn btn-primary" data-toggle="collapse" href="#faq2" role="button"
                        aria-expanded="false" aria-controls="faq2">How to create an event?</a>
                </p>
                <div class="collapse multi-collapse" id="faq2">
                    <div class="card card-body">
                        After signing up with an account, you will have a button on your profile for creating an event.
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <p>
                    <a class="btn btn-primary" data-toggle="collapse" href="#faq3" role="button"
                        aria-expanded="false" aria-controls="faq3">Is there a way to edit
                        my events?</a>
                </p>
                <div class="collapse multi-collapse" id="faq3">
                    <div class="card card-body">
                        After creating an event, on the event page, you can edit it, as long as signed up on the creator
                        account.
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <p>
                    <a class="btn btn-primary" data-toggle="collapse" href="#faq4" role="button"
                        aria-expanded="false" aria-controls="faq4">Who can attend
                        events?</a>
                </p>
                <div class="collapse multi-collapse" id="faq4">
                    <div class="card card-body">
                        Anyone who has signed up and clicked on the join button, on the event page.
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <p>
                    <a class="btn btn-primary" data-toggle="collapse" href="#faq5" role="button"
                        aria-expanded="false" aria-controls="faq5">How to contact the
                        website owners?</a>
                </p>
                <div class="collapse multi-collapse" id="faq5">
                    <div class="card card-body">
                        In the bottom of each page, there will be a Contact Us options, in which you will be able to
                        contact us
                        directly.
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <p>
                    <a class="btn btn-primary" data-toggle="collapse" href="#faq6" role="button" aria-expanded="false"
                        aria-controls="faq6">What can I do if there is a problem with an event?</a>
                </p>
                <div class="collapse multi-collapse" id="faq6">
                    <div class="card card-body">
                        In the event page there is a Report button. You can use it to report any problems so the administrators can contact the host and try to solve the problem or delete the event if necessary. 
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>

@endsection