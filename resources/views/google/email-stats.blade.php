@component('mail::message')
# Posting to Google+ Completed

All posts were processed!

@component('mail::button', ['url' => URL::to(route('google.stats', ['queue' => $q->id]))])
View Log
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent