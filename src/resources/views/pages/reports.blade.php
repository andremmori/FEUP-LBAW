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
            <h5 class="text-muted">All comment and event reports.</h5>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" onclick="location.href='{{route('admin.show')}}'"
                    class="btn btn-secondary">Main</button>
                <button type="button" class="btn btn-secondary active">Reports</button>
                <button type="button" onclick="location.href='{{route('admin.promotions')}}'" class="btn btn-secondary">Promotions</button>
                <button type="button" onclick="location.href='{{route('admin.bans')}}'" class="btn btn-secondary">Bans</button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-5">
            <div class="card border-darkly mb-3">
                <div class="card-header">Comment Reports: {{ count($comment_reports) }}</div>
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-bordered table-striped mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comment_reports as $report)
                            <tr>
                                <th scope="row"><a
                                        href="{{route('comment.show', $report->idcomment)}}">{{$report->idcomment}}</a>
                                </th>
                                <td>{{$report->description}}</td>
                                <td>{{$report->date}}</td>
                                <td>
                                    <form method="POST" action="{{route('comment.delete', $report->idcomment)}}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger">DEL</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card border-darkly mb-3">
                <div class="card-header">Event reports: {{ count($event_reports) }}</div>
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-bordered table-striped mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($event_reports as $report)
                            <tr>
                                <th scope="row"><a
                                        href="{{route('event.show', $report->idevent)}}">{{$report->idevent}}</a></th>
                                <td>{{$report->description}}</td>
                                <td>{{$report->date}}</td>
                                <td>
                                    <form method="POST" action="{{route('event.delete', $report->idevent)}}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger">DEL</button>
                                    </form>
                                </td>
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