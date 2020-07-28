@extends('layouts.app')

@section('content')
<div class="profilePage">
    <div class="main">
        <div class="jumbotron" style="margin-left: 3%;">
            <div class="profileCenter">
                <h1 style="padding-left: 5%">Profile</h1>
                <div class="img-hov">
                    <img style="width: 150px; height: 150px;" class="img-item" src="{{ asset('uploads/profile/'.$user->profilepicture) }}" alt="Profile Picture"><br>
                    <p class="imglabel"><a data-toggle="modal" data-target="#updatePic" href="" style="color: white">Update</a></p>
                </div>
            </div>
            <br>
            <div style=" margin-left: 30%">
                <button type="submit" class="btn btn-primary" onclick="location.href='{{ route('edit.show', $user->id)}}';">Edit Profile</button><br>
            </div>
            <br>
            <div class="form-group row" style="padding-left: 15%">
                <label for="user_name" class="col-sm-2 col-form-label">Name:</label>
                <div class="col-sm-10">
                    <input type="text" readonly="" class="form-control-plaintext" id="user_name" value="{{$user->name}}">
                </div>
                <label for="user_email" class="col-sm-2 col-form-label">Email:</label>
                <div class="col-sm-10">
                    <input type="text" readonly="" class="form-control-plaintext" id="user_email" value="{{$user->email}}">
                </div>
                <label for="user_date" class="col-sm-2 col-form-label">Birth:</label>
                <div class="col-sm-10">
                    <input type="date" readonly="" class="form-control-plaintext" id="user_date" value="{{$user->birthdate}}">
                </div>
                <label for="user_gender" class="col-sm-2 col-form-label">Gender: </label>
                <div class="col-sm-10">
                    <input type="text" readonly="" class="form-control-plaintext" style="padding-left: 5%" id="user_gender" value="{{$user->gender}}">
                </div>
            </div>
        </div>
    </div>
    <div class="owned">
        <button type="submit" class="btn btn-primary" onclick="location.href='{{route('create.show', $user->id)}}';" style="width:74%">Create your Own Event</button>
        <br>
        <h1>Your events:</h1>
        @if(count($user->hostedEvents) == 0)
        <h1><u>No owned events</u></h1>
        <br>
        @else
        @foreach ($user->hostedEvents as $event)
        <div class="card" style="max-width: 74%">
            <a href="{{route('event.show', $event->id)}}">
                <div class="card-body">
                    <h4 class="card-title">{{$event->name}}</h4>
                    <h6 class="card-subtitle mb-2 text-muted">{{$event->date}} @ {{$event->location->name}}, {{$event->location->country->name}}</h6>
                    <p class="card-text">{{$event->description}}</p>
                </div>
            </a>
        </div>
        @endforeach
        @endif
        <br>
        <h1>Events joined:</h1>
        @if(count($user->participatedEvents) == 0)
        <h1><u>No joined events</u></h1>
        @else
        @foreach ($user->participatedEvents as $event)
        <div class="card" style="max-width: 74%">
            <a href="{{route('event.show', $event->id)}}">
                <div class="card-body">
                    <h4 class="card-title">{{$event->name}}</h4>
                    <h6 class="card-subtitle mb-2 text-muted">{{$event->date}} @ {{$event->location->name}}, {{$event->location->country->name}}</h6>
                    <p class="card-text">{{$event->description}}</p>
                </div>
            </a>
        </div>
        @endforeach
        @endif
    </div>
</div>

<div id="updatePic" class="modal" role="dialog" style="padding-top: 10%">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update profile picture</h5>
                <button type="button" class="close" onclick="location.href='{{ route('profile.show', $user->id)}}';">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="input-group mb-3" style="padding-top: 5%">
                        <div class="custom-file">
                            <form method="post" action="{{route('profilePic')}}" enctype="multipart/form-data">
                                <div class="form-group">
                                    {{ csrf_field() }}
                                    <input type="file" id="profilepic" name="profilepic">
                                    <button style="display: inline;" class="btn btn-primary" type="submit" >Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </div>
</div>
@endsection