@extends('layouts.app')

@section('content')

    <google-plus-posting-panels
            service_id="{{ $service->id }}"
            accountsjson="{{ $accounts->toJson() }}"
            clientsjson="{{ $clients->toJson() }}"
            templatesjson="{{ $templates->toJson() }}">
    </google-plus-posting-panels>

@endsection