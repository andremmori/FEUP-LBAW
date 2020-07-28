<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8"/>

@extends('layouts.app')

@section('content')
<div class="createEvent" style="padding-left: 20%">
    <form method="POST" action="{{ route('event.update', $event->id) }}">
            {{ csrf_field() }}

        <div class="form-group" style="width:80%">
            <h1> Edit Event</h1>
            <br>
            <label for="name">Event Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="{{$event->name}}">
            <br>
            <div>
                <div class="form-group" style="width: 65%; display: inline-block">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="{{$event->address}}">
                </div>
                <div class="form-group" style="display:inline-block;">
                    <label for="country">Country</label>
                    <select class="form-control" id="country" name="country" >
                        <option value="">{{$event->location->country->name}}</option>
                        @foreach ($countries as $country)
                        <option value="{{$country->id}}">{{$country->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="display:inline-block;">
                    <label for="idLocation">City</label>
                    <select class="form-control" id="idLocation" name="idLocation" >
                        <option value="">{{$event->location->name}}</option>
                        @foreach ($cities as $city)
                        <option value="{{$city->id}}">{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="event-flex">
                <div class="event-box">
                    <label for="date">Date/Time</label>
                    <input name="date" id="date" class="form-control" type="datetime-local" value="{{ date( 'Y-m-d\TH:i:s', strtotime($event->date)) }}">
                </div>
                <div class="form-group" style="margin-left: 0.5%">
                    <label for="idCategory">Category</label>
                    <select class="form-control" id="idCategory" name="idCategory">
                        <option value="">{{$event->category->name}}</option>

                        @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-left: 0.5%">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" @if($event->price == null )placeholder="FREE"@else placeholder="{{$event->price}}€" @endif>
                </div>
                <div class="form-group" style="margin-left: 0.5%">
                    <label for="numberspots">Number of Spots</label>
                    <input type="number" class="form-control" id="numberspots" name="numberspots" placeholder="{{$event->numberspots}}">
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" placeholder="{{$event->description}}" rows="5"></textarea>
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn-primary" style="width:30%; margin-right: 15px">Update</button>
        or
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" style="width:30%; margin-left: 15px">Delete</button>
    </form>

</div>
<div id="deleteModal" class="modal" role="dialog" style="padding-top: 10%">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">WARNING</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Deleting the event is PERMANENT.</p>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{route('event.delete', $event->id)}}">
            {{ csrf_field() }}
            <div class="custom-control custom-checkbox" style="display: inline;">
                <input type="checkbox" class="custom-control-input" id="customCheck1" required>
                <label class="custom-control-label" for="customCheck1">I confirm the deletion of my event.</label>
            </div>
            <button type="submit" class="btn btn-danger" style="display: inline;">DELETE</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </form>
        </div>
    </div>
  </div>
</div>
@endsection