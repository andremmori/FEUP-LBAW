@extends('layouts.app')

@section('content')

    <div class="container" style="padding-top: 3%; padding-bottom: 3%;">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col">
                <h1>About Us</h1>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-1"></div>

            <div class="col-sm-5">
                <p>Meethology is a platform for those who want to have a good time, meet new people
                    and share their events. Founded by 3 Computer Engineering students with the purpose of making a
                    simple platform for everyone.
                    Everyone can participate in an event or create their own! Just create a free account and enjoy!
                </p>
            </div>
            <div class="col-sm-2"></div>
            <div class="col-sm-4">
                <img class="" src="{{asset('images/meethology.png')}}" alt="Meethology">
            </div>
        </div>
        <br>
        <!-- Cards -->
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col">
                <h2>Developers</h2>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-1"></div>

            <div class="col-sm-3">
                <div class="card">
                    <img class="card-img-top"
                        src="https://avatars3.githubusercontent.com/u/44000758?s=460&v=4"
                        alt="Card image" style="width:100%">
                    <div class="card-body">
                        <h4 class="card-title">André Mori</h4>
                        <p class="card-text">3rd year Informatics Engineering student at FEUP and developer of Meethology. 
                        </p>
                        <a href="https://github.com/andremmori" class="stretched-link">Github</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-3">
                <div class="card">
                    <img class="card-img-top"
                        src="https://avatars0.githubusercontent.com/u/37114874?s=460&v=4"
                        alt="Card image" style="width:100%">
                    <div class="card-body">
                        <h4 class="card-title">Carlos Soeiro</h4>
                        <p class="card-text">3rd year Informatics Engineering student at FEUP and developer of Meethology. 
                        </p>
                        <a href="https://github.com/CMGS5" class="stretched-link">Github</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-3">
                <div class="card">
                    <img class="card-img-top"
                        src="https://avatars3.githubusercontent.com/u/44318442?s=460&u=a74a322d51724c90ffac57905ba388e39867cfcc&v=4"
                        alt="Card image" style="width:100%">
                    <div class="card-body">
                        <h4 class="card-title">Gustavo Tavares</h4>
                        <p class="card-text">3rd year Informatics Engineering student at FEUP and developer of Meethology. 
                        </p>
                        <a href="https://github.com/GustavoSTT" class="stretched-link">Github</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection