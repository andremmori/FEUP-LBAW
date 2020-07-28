<!DOCTYPE html>
@extends('layouts.app')

@section('content')

    <div class="container" style="padding-top: 3%; padding-bottom: 3%;">
        <form method="POST" action="{{route('contact.send')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm">
                    <h1>Contact Us</h1>

                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <p>Please enter any doubts, comments or complaints in the form below so our team can contact you as soon as possible to answer your submission.
                        Remember to enter a <strong>valid</strong> email below so we can contact you back.
                    </p>
            
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <div class="form-group">
                        <label for="contact_email">Email address</label>
                        <input type="email" class="form-control" name="contact_email" id="contact_email"
                            placeholder="Enter email" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input class="form-control" name="subject" id="subject" maxlength="50"
                            required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <div class="form-group">
                        <label for="reason">Reason of contact</label>
                        <textarea class="form-control" style="resize: none;" name="reason" id="reason" rows="3" maxlength="250"
                            required></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                </div>
            </div>
        </form>
    </div>

@endsection
