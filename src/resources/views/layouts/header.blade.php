<html lang="en">

<head>
    <title>Meethology</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://kit.fontawesome.com/b0006889a0.js" crossorigin="anonymous"></script>
    <script src={{asset('js/form.js')}}></script>
    <script src={{asset('js/app.js')}}></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!--HEADER-->
    <nav class="navbar navbar-dark bg-primary">
        <a class="navbar-brand" href="{{route('home.show')}}"><b>MEET</b>HOLOGY</a>
        <form class="form-inline my-2 my-lg-0">
            @if(!Auth::check())
            <button class="btn btn-dark my-2 my-sm-0" type="button" data-toggle="modal" data-target="#login">Sign
                In</button>
            <button class="btn btn-secondary my-2 my-sm-0 ml-1" type="button" data-toggle="modal"
                data-target="#signup">Sign Up</button>
            @else
            @if(!Auth::user()->admin)
            <button class="btn btn-dark my-2 my-sm-0" type="button"
                onclick="location.href='{{route('profile.show', Auth::user()->id)}}'"> {{Auth::user()->name}} </button>
            @else
            <button class="btn btn-dark my-2 my-sm-0" type="button" onclick="location.href='{{route('admin.show')}}'">
                {{Auth::user()->name}} </button>
            @endif
            <button class="btn btn-secondary my-2 my-sm-0 ml-1" type="button"
                onclick="location.href='{{url('logout')}}'">Sign Out</button>
            @endif
        </form>
    </nav>
    <!--END_HEADER-->

    <div class="modal fade" id="login">
        <div class="modal-dialog modal-sm" style="width: 100%;" role="document">
            <div class="modal-content" style="border-radius: 25px; border-width: 0px;">
                <form class="form-container" method="POST" action="{{ route('login') }}">
                    <div class="modal-body">
                        {{ csrf_field() }}

                        <h1>Sign In <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </h1>
                        <br>
                        <label for="email">E-mail</label>
                        <input class="form-control" id="email" type="text" name="email" value="{{ old('email') }}"
                            required>

                        <label for="password">Password</label>
                        <input class="form-control" id="password" type="password" name="password" required>
                        @if ($errors->has('email'))
                        <span class="text-danger">
                            {{ $errors->first('email') }}
                        </span>
                        <br>
                        @elseif ($errors->has('banned'))
                        <span class="text-danger">
                            {{ $errors->first('banned') }}
                        </span>
                        <br>
                        @endif
                        <a href="{{route('password.recover.form')}}">Forgot your password?</a>

                        <br><br>
                        <button type="submit" class="btn">Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="signup">
        <div class="modal-dialog modal-lg" style="width: 100%;" role="document">
            <div class="modal-content" style="border-radius: 25px; border-width: 0px;">
                <form class="form-container" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-11">
                                    <h1>Sign Up</h1>
                                </div>
                                <div class="col-1">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-6">
                                    <label for="name">Name</label>
                                    <input class="form-control" id="name" type="text"
                                        oninput="this.setCustomValidity('')"
                                        oninvalid="this.setCustomValidity('Please enter a valid name')"
                                        pattern="^[a-zA-Z][a-zA-Z -]*$" name="name"
                                        value="{{ old('name') }}" required>
                                </div>
                                <div class="col-6">
                                    <label for="email">E-Mail Address</label>
                                    <input class="form-control" id="email" type="email" name="email"
                                        value="{{ old('email') }}" required>
                                    @if ($errors->has('email') and $errors->first('email') != 'These credentials do not
                                    match
                                    our
                                    records.')
                                    <span class="text-danger">
                                        {{ $errors->first('email') }}
                                    </span>
                                    <br>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-6">
                                    <script>$(function () {
                                            $('[data-toggle="tooltip"]').tooltip()
                                        })</script>
                                    <label for="password">Password <a title="8 or more characters" data-toggle="tooltip"
                                            data-placement="top"><i class="fa fa-info-circle"
                                                aria-hidden="true"></i></a></label>
                                    <input class="form-control" id="password" type="password"
                                        oninput="this.setCustomValidity('')"
                                        oninvalid="this.setCustomValidity('Password must have at least 8 characters')"
                                        pattern=".{8,}" name="password" required>
                                </div>
                                <div class="col-6">
                                    <label for="password-confirm">Confirm Password</label>
                                    <input class="form-control" id="password-confirm" type="password"
                                        name="password_confirmation" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-6">
                                    <label for="birthdate">Enter BirthDate</label>
                                    <input id="birthdate" name="birthdate" min="1900-01-01"
                                        max="@php echo date('Y-m-d')@endphp" class="form-control" type="date" required>
                                    <script>
                                        $("#birthdate").validate();
                                    </script>
                                    @if ($errors->has('birthdate'))
                                    <span class="text-danger">
                                        {{ $errors->first('birthdate') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <label for="gender">Enter Gender</label>
                                    <div class="genderProfile">
                                        <div id="genderdiv">
                                            <input id="gender" type="radio" name="gender" value="M" required> Male
                                            <input type="radio" name="gender" value="F" required> Female
                                            <input type="radio" name="gender" value="O" required> Other
                                        </div>
                                    </div>
                                    @if ($errors->has('gender'))
                                    <span class="error">
                                        {{ $errors->first('gender') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn">Sign Up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</body>

</html>