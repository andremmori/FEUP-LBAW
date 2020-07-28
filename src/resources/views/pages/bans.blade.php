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
            <h5 class="text-muted">Here you can ban or unban a user.</h5>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" onclick="location.href='{{route('admin.show')}}'"
                    class="btn btn-secondary">Main</button>
                <button type="button" onclick="location.href='{{route('admin.reports')}}'"
                    class="btn btn-secondary">Reports</button>
                <button type="button" onclick="location.href='{{route('admin.promotions')}}'"
                    class="btn btn-secondary">Promotions</button>
                <button type="button" class="btn btn-secondary active">Bans</button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-6">
            <div class="card border-darkly mb-3">
                <div class="card-header">Users: {{ count($users->where('banned', 0)) }}</div>
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-bordered table-striped mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Ban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users->sortBy('id')->where('banned', 0) as $user)
                            <tr>
                                <th scope="row"><a href="{{route('profile.show', $user->id)}}">{{$user->id}}</a></th>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    @if(!$user->admin)
                                    <form method="POST" action="{{route('admin.ban', $user->id)}}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger">BAN</button>
                                    </form>
                                    @else
                                    <h4><span class="badge badge-secondary">ADM</span></h4>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-darkly mb-3">
                <div class="card-header">Banned Users: {{ count($users->where('banned', 1)) }}</div>
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-bordered table-striped mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#id</th>
                                <th scope="col">Email</th>
                                <th scope="col">Unban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users->sortBy('id')->where('banned', 1) as $user)
                            <tr>
                                <th scope="row"><a href="{{route('profile.show', $user->id)}}">{{$user->id}}</a></th>
                                <td>{{$user->email}}</td>
                                <td>
                                    <form method="POST" action="{{route('admin.unban', $user->id)}}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-success">UNBAN</button>
                                    </form>
                                </td>
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