@extends('layouts.app')

@section('content')

    <google-plus-posting-panels
            service_id="{{ $service->id }}"
            accountsjson="{{ json_encode($accounts) }}"
            clientsjson="{{ json_encode($clients) }}"
            templatesjson="{{ json_encode($templates) }}">
    </google-plus-posting-panels>

@endsection