@extends('layouts.app')

@section('content')

<body>
    <form method="POST" action="{{route('password.reset', $id)}}">
        {{ csrf_field() }}
        <div class="container">
            <div class="row" style="margin-top: 5%">
                <div class="col-md-3"></div>
                <div class="col">
                    <h1>Reset Password</h1>
                </div>
            </div>
            <div class="row" style="margin-top: 3%">
                <div class="col-md-3"></div>
                <div class="col-md-8">
                    <h5>Enter your new password.
                    </h5>
                </div>
            </div>
            <div class="row" style="margin-top: 3%; margin-bottom: 2%">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password (At least 8 characters, one uppercase letter and one
                            number)</label>
                        <input type="password" name="password" class="form-control" id="password"
                            placeholder="Enter your password" oninput="this.setCustomValidity('')"
                            oninvalid="this.setCustomValidity('Password must have at least 8 characters, one uppercase letter and one number')"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 5%">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Confirm password</label>
                        <input type="password" onChange="checkPasswordMatch();" name="confirm" class="form-control" id="confirm_password"
                            placeholder="Enter your password" required>
                        <div id="passwordmatch"></div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 3%; margin-bottom: 9%">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </form>




</body>
@endsection