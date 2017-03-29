@component('mail::message')
# Posting to Linkedin Completed

All posts were processed!

@component('mail::button', ['url' => URL::to(route('linkedin.stats', ['queue' => $q->id]))])
View Log
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent