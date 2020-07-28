@extends('layouts.app')

@section('content')
@if($errors->has('banned') or $errors->first('email') == 'These credentials do not match our records.')
    <script type="text/javascript">
    openLogin();
    </script>
@elseif($errors->has('email') or $errors->has('birthdate'))
    <script type="text/javascript">
    openSign();
    </script>

@endif

    <!-- SEARCH BAR -->
    <section>
        <div>
        <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active">
                        </li>
                        <li data-target="#myCarousel" data-slide-to="1">
                        </li>
                        <li data-target="#myCarousel" data-slide-to="2">
                        </li>
                        <li data-target="#myCarousel" data-slide-to="3">
                        </li>
                        <li data-target="#myCarousel" data-slide-to="4">
                        </li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item  active"
                            style="width: 100%; height: 800px; background-size: cover; background-image: url({{asset('images/main.jpg')}})">
                            <div class="container">
                                <div class="carousel-caption text-left">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item"
                            style="width: 100%; height: 800px; background-size: cover; background-image: url({{asset('images/main2.jpg')}})">
                            <div class="container">
                                <div class="carousel-caption text-left">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item"
                            style="width: 100%; height: 800px; background-size: cover; background-image: url({{asset('images/main3.jpg')}})">
                            <div class="container">
                                <div class="carousel-caption text-left">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item"
                            style="width: 100%; height: 800px; background-size: cover; background-image: url({{asset('images/main5.jpg')}})">
                            <div class="container">
                                <div class="carousel-caption text-left">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item"
                            style="width: 100%; height: 800px; background-size: cover; background-image: url({{asset('images/main6.jpg')}})">
                            <div class="container">
                                <div class="carousel-caption text-left">
                                </div>
                            </div>
                        </div>
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


            <br><br><br>

            <div class="search-event">
                    <div class="container">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header bg-primary" style="word-spacing: 210px"> Search In On</div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{route('event.search')}}">
                            {{ csrf_field() }}
                        <div class="form-group row">
                            <div class="col-xs-6" >
                                <input class="form-control" type="text" name="event" placeholder="Event">
                            </div>
                            <div class="col-xs-2" >
                                <input class="form-control" type="text" name="location" placeholder="Location">
                            </div>
                            <div class="col-xs-1" >
                                <input class="form-control" placeholder="Date" name="date" type="text" onfocus="(this.type='date')" onblur="(this.type='text')">
                            </div>
                            <div class="col-xs-1" >
                            </a><button class="btn btn-primary" type="submit"><i style="color: white"
                        class="fas fa-search"></i></button>
                            </div>
                        </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN EVENTS -->

    <section>   
        <h2 class="main-text">Sports</h2>
        <div class="main-event">
            <div>
                @foreach($sports as $event)
                <div class="card w-75" style="max-width: 30%; height: 200px; min-width:300px; display: inline-block; margin-right: 1%; margin-bottom: 2%; vertical-align: bottom; overflow-y: auto;">
                    <a href="{{route('event.show', $event->id)}}">
                            
                        <div class="card-body" >
                            <h4 class="card-title">{{$event->name}}</h4>
                            <h6 class="card-subtitle mb-2 text-muted">{{$event->date}} @ {{$event->location->name}}, {{$event->location->country->name}}</h6>
                            <p class="card-text" style="overflow: hidden;">{{$event->description}}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <h2 class="main-text">Music</h2>
        <div class="main-event">
            <div>
                @foreach($music as $event)
                <div class="card w-75" style="max-width: 30%; height: 200px; min-width:300px; display: inline-block; margin-right: 1%; margin-bottom: 2%; vertical-align: bottom; overflow-y: auto;">
                    <a href="{{route('event.show', $event->id)}}">
                        <div class="card-body">
                            <h4 class="card-title">{{$event->name}}</h4>
                            <h6 class="card-subtitle mb-2 text-muted">{{$event->date}} @ {{$event->location->name}}, {{$event->location->country->name}}</h6>
                            <p class="card-text">{{$event->description}}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <h2 class="main-text">Film</h2>
        <div class="main-event">
            <div>
                @foreach($film as $event)
                <div class="card w-75" style="max-width: 30%; height: 200px; min-width:300px; display: inline-block; margin-right: 1%; margin-bottom: 2%; vertical-align: bottom; overflow-y: auto;">
                    <a href="{{route('event.show', $event->id)}}">
                        <div class="card-body">
                            <h4 class="card-title">{{$event->name}}</h4>
                            <h6 class="card-subtitle mb-2 text-muted">{{$event->date}} @ {{$event->location->name}}, {{$event->location->country->name}}</h6>
                            <p class="card-text">{{$event->description}}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <h2 class="main-text">Social</h2>
        <div class="main-event">
            <div>
                @foreach($social as $event)
                <div class="card w-75" style="max-width: 30%; height: 200px; min-width:300px; display: inline-block; margin-right: 1%; margin-bottom: 2%; vertical-align: bottom; overflow-y: auto;">
                    <a href="{{route('event.show', $event->id)}}">
                        <div class="card-body">
                            <h4 class="card-title">{{$event->name}}</h4>
                            <h6 class="card-subtitle mb-2 text-muted">{{$event->date}} @ {{$event->location->name}}, {{$event->location->country->name}}</h6>
                            <p class="card-text">{{$event->description}}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <h2 class="main-text">Learning</h2>
        <div class="main-event">
            <div>
                @foreach($learning as $event)
                <div class="card w-75" style="max-width: 30%; height: 200px; min-width:300px; display: inline-block; margin-right: 1%; margin-bottom: 2%; vertical-align: bottom; overflow-y: auto;">
                    <a href="{{route('event.show', $event->id)}}">
                        <div class="card-body">
                            <h4 class="card-title">{{$event->name}}</h4>
                            <h6 class="card-subtitle mb-2 text-muted">{{$event->date}} @ {{$event->location->name}}, {{$event->location->country->name}}</h6>
                            <p class="card-text">{{$event->description}}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
