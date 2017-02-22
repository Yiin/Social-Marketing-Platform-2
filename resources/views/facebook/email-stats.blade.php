@component('mail::message')
# Posting to Facebook Completed

All posts were processed!

@component('mail::button', ['url' => URL::to(route('facebook.stats', ['queue' => $q->id]))])
View Log
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent