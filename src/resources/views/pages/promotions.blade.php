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
            <h5 class="text-muted">Information about promotions and demotions.</h5>
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
                <button type="button" class="btn btn-secondary active">Promotions</button>
                <button type="button" onclick="location.href='{{route('admin.bans')}}'"
                    class="btn btn-secondary">Bans</button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-6">
            <div class="card border-darkly mb-3">
                <div class="card-header">Users: {{ count($users) }}</div>
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-bordered table-striped mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Promote/Demote</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users->sortBy('id') as $user)
                            <tr>
                                <th scope="row"><a href="{{route('profile.show', $user->id)}}">{{$user->id}}</a></th>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                @if($user->id != Auth::user()->id)
                                    @if(!$user->admin)
                                    <td>
                                        <form method="POST" action="{{route('admin.promote', $user->id)}}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-success">PROMOTE</button>
                                        </form>
                                    </td>
                                    @else
                                    <td>
                                        <form method="POST" action="{{route('admin.demote', $user->id)}}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger">DEMOTE</button>
                                        </form>
                                    </td>
                                    @endif
                                @else
                                <td></td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-darkly mb-3">
                <div class="card-header">Promotions/Demotions</div>
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-bordered table-striped mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#id</th>
                                <th scope="col">Creator</th>
                                <th scope="col">Receiver</th>
                                <th scope="col">Date</th>
                                <th scope="col">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($promotions as $promotion)
                            <tr>
                                <th scope="row">{{$promotion->id}}</th>
                                <td>{{$users->find($promotion->idcreator)->name}}</td>
                                <td>{{$users->find($promotion->idreceiver)->name}}</td>
                                <td>{{$promotion->date}}</td>
                                @if($promotion->promoted)
                                <td>Promoted</td>
                                @else
                                <td>Demoted</td>
                                @endif
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