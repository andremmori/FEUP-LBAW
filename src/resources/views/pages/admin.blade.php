@extends('layouts.app')

@section('content')

<div class="container-fluid" style="padding-top: 3%; padding-bottom: 3%;">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <h1>Administrator</h1>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <h5 class="text-muted">Information about Users and Events.</h5>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-secondary active">Main</button>
                <button type="button" onclick="location.href='{{route('admin.reports')}}'"
                    class="btn btn-secondary">Reports</button>
                <button type="button" onclick="location.href='{{route('admin.promotions')}}'"
                    class="btn btn-secondary">Promotions</button>
                <button type="button" onclick="location.href='{{route('admin.bans')}}'"
                    class="btn btn-secondary">Bans</button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-5">
            <div class="card border-darkly mb-3">
                <div class="card-header">Users: {{ count($users) }}</div>
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-bordered table-striped mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users->sortBy('id') as $user)
                            <tr>
                                <th scope="row"><a href="{{route('profile.show', $user->id)}}">{{$user->id}}</a></th>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card border-darkly mb-3">
                <div class="card-header">Events: {{ count($events) }}</div>
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-bordered table-striped mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $event)
                            <tr>
                                <a>
                                    <th scope="row"><a href="{{route('event.show', $event->id)}}">{{$event->id}}</a>
                                    </th>
                                    <td>{{$event->name}}</td>
                                    <td>{{$event->date}}</td>
                                </a>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection