@extends('layouts.app')

@section('content')

<body>
    <form method="POST" action="{{route('password.recover')}}">
        {{ csrf_field() }}
        <div class="container">
            <div class="row" style="margin-top: 10%">
                <div class="col-md-2"></div>
                <div class="col">
                    <h1>Recover Password</h1>
                </div>
            </div>
            @if (session()->has('success'))
            <div class="row" style="margin-top: 3%">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <div class="alert alert-dismissible alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Email sent!</strong> Please check your email for instructions.
                    </div>
                </div>
            </div>
            @elseif (session()->has('error'))
            <div class="row" style="margin-top: 3%">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <div class="alert alert-dismissible alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>User not found.</strong> There is no registered account with the email inserted.
                    </div>
                </div>
            </div>
            @endif

            <div class="row" style="margin-top: 3%">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <h5>Enter your email address below, and we'll email you instructions on how to change your password.
                    </h5>
                </div>
            </div>
            <div class="row" style="margin-top: 3%; margin-bottom: 5%">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email">
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 3%; margin-bottom: 9%">
                <div class="col-md-2"></div>
                <div class="col-md-3">
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </form>

</body>
@endsection