{{-- Do not use excess indentation when writing Markdown emails. Markdown parsers will render indented content as code blocks. --}}

@component('mail::message')

# You can now login to Social Media Panel using details below

Login name: {{ $email }}

Your password: {{ $password }}

@component('mail::button', ['url' => URL::to(route('login'))])
Login!
@endcomponent

{{ config('app.name') }}

@endcomponent