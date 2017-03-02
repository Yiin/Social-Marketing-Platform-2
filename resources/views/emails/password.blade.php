@component('mail::message')
# You can now login to Social Media Panel using details below

Login name: $email <br/>
Your password: $password

@component('mail::button', ['url' => URL::to(route('login'))])
Login!
@endcomponent

{{ config('app.name') }}
@endcomponent