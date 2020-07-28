@component('mail::message')
# Hey, __{{$user->name}}__!

There was a request to reset your __Meethology__ account password.

Please click the button below to proceed.

@component('mail::button', ['url' => route('password.reset.form', Crypt::encrypt($user->id))])
Reset Password
@endcomponent

If you did not ask for a reset, please ignore this email.

Thanks,<br>
{{ config('app.name') }}® Team
@endcomponent
