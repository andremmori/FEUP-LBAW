@extends('layouts.app')

@section('content')
<main>
    @if(session()->has('success'))
    <div class="container" style="padding-top: 2%">
        <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h1><strong> Payment Suceesful!</strong></h1><br>
            <h4>Your payment was approved with refence "{{session()->get('success')}}".</h4>
        </div>
    </div>
    @elseif(session()->has('error'))
    <div class="container" style="padding-top: 2%">
        <div class="alert alert-dismissible alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h1><strong> Payment Failed.</strong></h1><br>
            <h4>Your payment with error "{{session()->get('error')}}" was not approved. Please try again to confirm your
                participation.</h4>
        </div>
    </div>
    @endif
    <div class="container" style="padding-top: 2%">
        <h1>{{$event->name}}<span class="badge badge-secondary"
                style="margin-left: 1%">{{$event->category->name}}</span></h1>
    </div>
    <hr>
    <div class="container" style="padding-top: 2%">
        @if(Auth::check() && ($event->hosts->contains(Auth::user()->id) != null || Auth::user()->admin))
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-7">
                    <h1>Hello, {{Auth::user()->name}}</h1><br>
                    <h4 class="text-muted">There are {{count($event->participants)}} participants coming to your
                        event! Everything ready?</h4>
                    <h4 class="text-muted">People will be more interested to join your event with more pictures and
                        information, add some more!</h4>
                </div>
                <div class="col-md-5">
                    <h1><b>Edit Section</b></h1>
                    <br>
                    <h4 class="text-muted">Want to change something? <button class="btn btn-primary"
                            onclick=" location.href='{{ route('event.edit', $event->id) }}' ">Edit Event</button>
                    </h4>
                    <br>
                    <h4 class="text-muted"> Add another picture for your event!</h4><br>
                    <form method="post" action="{{route('eventPic')}}" enctype="multipart/form-data">
                        <div class="form-group">
                            {{ csrf_field() }}
                            <input type="file" id="eventpic" name="eventpic" style="display: inline; width: 100%"
                                required>
                            <input type="hidden" name="eventid" value="{{ $event->id }}">
                            <button class="btn btn-primary" type="submit">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <hr>
        @endif
        <div class="row">
            <div class="col-md-7">
                <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">
                    <ol class="carousel-indicators">
                        @foreach($event->photos as $key => $photo)
                        <li data-target="#myCarousel" data-slide-to="{{$key}}" class="{{$key == 0 ? 'active' : ''}}">
                        </li>
                        @endforeach
                    </ol>
                    <div class="carousel-inner">
                        @foreach($event->photos as $key => $photo)
                        <div class="carousel-item  {{$key == 0 ? 'active' : ''}}"
                            style="width: 100%; height: 400px; background-size: cover; background-image: url({{asset('uploads/event/'.$photo->name)}})">
                            <div class="container">
                                <div class="carousel-caption text-left">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <h3 class="text-muted">{{$event->address}}<br>{{$event->location->name}},
                    {{$event->location->country->name}}</h3>
                <p class="lead text-muted"><i class="far fa-calendar-alt" style="width: 10%"></i>
                    {{$event->date}}</p>
                <p class="lead text-muted"><i class="fas fa-euro-sign" style="width: 10%"></i> @if($event->price
                    == null) FREE @else {{sprintf("%.2f", $event->price)}}€@endif</p>
                <p class="lead text-muted"><i class="fas fa-user-friends" style="width: 10%"></i>
                    {{$event->numberspots}} spots left</p>
                <h4 class="text-muted">
                    Hosts:</h4>
                @foreach ($event->hosts as $host)
                <img alt="Host Picture" style="width: 40px; height: 40px; margin-bottom: 2%" class="img-item"
                    src="{{ asset('uploads/profile/'.$host->profilepicture) }}">
                <span class="lead text-muted" style="margin-left: 2%;">{{$host->name}}</span>
                @endforeach
            </div>
            @if(Auth::check())
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                    data-target="#ReportEvent"> Report </button></div>
            @endif
        </div>

        <hr>
        <br>
        <div class="row">
            <div class="col-md">
                <h3 class="text-muted">{{$event->description}}</h3>
            </div>
        </div>
        <br>
        <hr>

        <div class="row">
            @if(!Auth::check())
            <div class="col-md-7" style="padding-top: 1%; padding-bottom: 1%">
                <h2>Sign In or create a free account to participate!</h2>
            </div>
            @elseif(!($event->hosts->contains(Auth::user()->id) != null || Auth::user()->admin))
            <div class="col-md-5" style="padding-top: 1%; padding-bottom: 1%">
                @if(!Auth::user()->participatedEvents->contains($event))
                <h1>Interested? Join the event!</h1>
                @if($event->price == null)
                <form method="POST" action="{{route('participate')}}">
                    {{ csrf_field() }}
                    <input type="hidden" id="idUser" name="idUser" value="{{Auth::user()->id}}">
                    <input type="hidden" id="idEvent" name="idEvent" value="{{$event->id}}">
                    <button type="submit" class="btn btn-success btn-lg ">Participate</button>
                </form>
                @else
                <form method="POST" action="{{route('paypal.pay')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="price" value="{{$event->price}}">
                    <input type="hidden" name="event" value="{{$event->id}}">
                    <input type="hidden" name="user" value="{{Auth::user()->id}}">
                    <button type="submit" class="btn btn-success btn-lg ">Buy Ticket</button>
                </form>
                @endif
                @else
                <h1>You're in!</h1>
                <form method="POST" action="{{route('cancel')}}">
                    {{ csrf_field() }}
                    <input type="hidden" id="idUser" name="idUser" value="{{Auth::user()->id}}">
                    <input type="hidden" id="idEvent" name="idEvent" value="{{$event->id}}">
                    <button type="submit" class="btn btn-danger btn-lg ">Cancel Participation</button>
                </form>
                @endif
                <br>
                <div class="row">
                    <div class="col">
                        <h3 class="text-muted">Invite your friends:
                            <button type="submit" class="btn btn-primary" data-toggle="modal"
                                data-target="#invite">Invite</button>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-7" style="padding-top: 1%; padding-bottom: 1%">
                <h4 class="text-muted">Participants ({{count($event->participants)}})
                    <button type="button" style="float: right; color: #007bff; background:none; border:none;"
                        data-toggle="modal" data-target="#Participants"> View More </button>
                </h4>
                <div class="row">
                    @foreach ($event->participants as $participant)
                    @if($loop->iteration > 8)
                    @break
                    @endif
                    <div class="col-md-3" style="padding-top: 1%; padding-bottom: 1%">
                        <img alt="Participant Picture"
                            style="display: block; margin-left: auto; margin-right: auto; width: 40px; height: 40px;"
                            class="img-item" src="{{ asset('uploads/profile/'.$participant->profilepicture) }}">
                        <p class="lead text-muted" style="text-align: center; overflow-y: auto;">{{$participant->name}}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        <br>

        <hr>

        @if(Auth::check())
        <div class="row">
            <div class="col-md">
                <form method="POST" action="{{ route('comment', $event->id)}}">
                    {{ csrf_field() }}
                    <fieldset>
                        <h1>Doubts? Leave a comment</h1><br>
                        <div class="form-group">
                            <textarea name="comment" maxlength="150" placeholder="Add a comment" class="form-control"
                                id="exampleTextarea" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary ">Publish</button>
                    </fieldset>
                </form>
            </div>
        </div>
        @endif

        <hr>

        <div class="row">
            <div class="col-md" style="padding-top: 1%; padding-bottom: 1%">
                <h1>Comments:</h1>
                @if(count($event->comments) == 0)
                <h2 class="text-muted">No Comments Yet</h2>
                @endif
            </div>
            <br>
            <hr>
        </div>

        @foreach ($event->comments->sortByDesc('date') as $comment)
        <div class="row">
            <div class="col-md" style="padding-top: 1%; padding-bottom: 1%;">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-11 ">
                                    <h3 class="card-title">{{$comment->user->name}}</h3>
                                </div>
                                <div class="col-md-1">
                                    <a class="btn" data-toggle="modal" data-target="#ReportComment"><i
                                            class='fa fa-ellipsis-v'></i></a>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-7">
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        {{date_format(date_create($comment->date), "Y/m/d H:i:s")}}</h6>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-text">{{$comment->text}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</main>


<div class="modal fade" id="Participants">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ">
            <div class="modal-body ">
                <div class="row">
                    <div class="col-md-7">
                        <h4>Participants ({{count($event->participants)}})</h4><br>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    @foreach ($event->participants as $participant)
                    <div class="col-md-3" style="padding-top: 1%; padding-bottom: 1%">
                        <img alt="Profile Picture"
                            style="display: block; margin-left: auto; margin-right: auto; width: 40px; height: 40px;"
                            class="img-item" src="{{ asset('uploads/profile/'.$participant->profilepicture) }}">
                        <p class="lead text-muted" style="text-align: center; overflow-y: auto;">{{$participant->name}}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ReportEvent">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{route('event.report', $event->id)}}">
            {{ csrf_field() }}
            <div class="modal-content ">
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-md-10">
                            <h1>Report Event</h1><br>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <textarea rows="4" cols="50" name="description" maxlength="150"
                                placeholder="Please describe your report" style="resize: none; border-color: lightgrey"
                                required></textarea><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-9"></div>
                        <div class="col">
                            <br><button type="submit" class="btn btn-danger">Report</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="ReportComment">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('reportComment', $event->id) }}">
            {{ csrf_field() }}
            <div class="modal-content ">
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-md-10">
                            <h1>Report Comment</h1><br>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <textarea rows="4" cols="50" name="description" maxlength="150"
                                placeholder="Please describe your report" style="resize: none; border-color: lightgrey"
                                required></textarea><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-9"></div>
                        <div class="col">
                            <br><button type="submit" class="btn btn-danger">Report</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="ReportComment1">
    <div class="form-popup" id="ReportForm2">
        <form class="form-container" method="POST" action="{{ route('reportComment', $event->id) }}">
            {{ csrf_field() }}

            <h1>Report</h1>
            <input type="hidden" id="CommentId" name="commentId">
            <label for="description"></label>
            <input type="Text" id="description" name="description" placeholder="Description"><br>

            <button type="submit" class="btn btn-danger " style='background-color: #E74C3C;'>Report</button>
        </form>
    </div>
</div>

<div class="modal fade" id="invite">
    <div class="modal-dialog">
        <form method="POST" action="{{route('event.invite', $event->id)}}">
            {{ csrf_field() }}
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Invite</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <label for="email">Email to invite:</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Send</button>
                </div>

            </div>
        </form>
    </div>
</div>
@if(Auth::check())
<div class="modal fade" id="payment">
    <div class="modal-dialog modal-dialog-centered">
        <div class="form-popup">
            <div class="modal-content ">
                <form method="POST" action="{{route('paypal.pay')}}">
                    {{csrf_field()}}
                    <div class="modal-body ">
                        <input type="hidden" name="price" value="{{$event->price}}">
                        <input type="hidden" name="event" value="{{$event->id}}">
                        <input type="hidden" name="user" value="{{Auth::user()->id}}">
                        <div class="row">
                            <div class="col-md-7">
                                <h1>Payment</h1><br>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-9">
                                <h2 class="text-muted">
                                    Price: {{$event->price}}€
                                </h2>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6"></div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary btn-lg">Pay with PayPal</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection