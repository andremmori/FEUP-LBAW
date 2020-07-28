@component('mail::message')
# Hey!

Your friend __{{$user->name}}__ invited you to join an event called __{{$event->name}}__ in __{{$event->location->name}}__, __{{$event->location->country->name}}__ at __{{$event->date}}__.

@component('mail::button', ['url' => route('event.show', $event->id)])
Check it out!
@endcomponent

Please ignore this email if you don't think it was supposed to reach you.

{{ config('app.name') }}® Team
@endcomponent