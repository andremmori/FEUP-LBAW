@extends('layouts.app')

@section('content')
<div class="createEvent" style="padding-left: 30%; padding-top: 5%">
    <form method="POST" action="{{ route('profile.update', $user->id) }}">
            {{ csrf_field() }}
            
            <div class="form-group" style="width:80%">
                <h1> Edit Profile</h1>
                <br>
                <input type="hidden" class="form-control" id="id" name="id" value="{{$user->id}}" >
            <div>
                <div class="form-group" style="width: 40%; display: inline-block;">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="{{$user->name}}" >
                </div>
                <div class="form-group" style="width: 40%; display: inline-block">
                    <label for="address">Email</label>
                    <input type="email" class="form-control" id="address" name="address" placeholder="{{$user->email}}" >
                </div>
            </div>
            <div>
                <div class="form-group" style="width: 40%; display: inline-block;">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" >
                </div>
                <div class="form-group" style="width: 40%; display: inline-block">
                    <label for="address">Retype Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Retype Password" >
                </div>
            </div>
            <div>
                <div class="event-flex" style="display: inline-block">
                    <div class="event-box">
                        <label for="date">Birthday</label>
                        <input name="date" id="date" class="form-control" type="date" value="{{$user->birthdate}}" >
                    </div>
                </div>

                <div class="form-group" style="display: inline-block; padding-left: 18%"><br>
                    <label for="gender">Gender</label>
                    <div>
                        <input id="gender" type="radio" name="gender" value="M" @if($user->gender == 'M') checked @endif> Male
                        <input type="radio" name="gender" value="F" @if($user->gender == 'F') checked @endif> Female
                        <input type="radio" name="gender" value="O" @if($user->gender == 'O') checked @endif> Other
                    </div>            
                </div>
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn-success" style="width:30%; margin-right: 0.5rem">Update</button> OR
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" style="width:30%; margin-left: 0.5rem">Delete</button>
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
        <p>Deleting the account is PERMANENT.</p>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{route('user.delete')}}">
            {{ csrf_field() }}
            <div class="custom-control custom-checkbox" style="display: inline;">
                <input type="checkbox" class="custom-control-input" id="customCheck1" required>
                <label class="custom-control-label" for="customCheck1">I confirm the deletion of my account.</label>
            </div>
            <input type="hidden" name="userId" value="{{$user->id}}">
            <button type="submit" class="btn btn-danger" style="display: inline;">DELETE</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </form>
        </div>
    </div>
  </div>
</div>
@endsection