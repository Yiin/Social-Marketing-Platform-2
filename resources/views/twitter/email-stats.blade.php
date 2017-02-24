@component('mail::message')
# Tweeting Completed

All tweets were processed!

@component('mail::button', ['url' => URL::to(route('twitter.stats', ['queue' => $q->id]))])
View Log
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent