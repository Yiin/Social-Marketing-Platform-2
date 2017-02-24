@extends('layouts.app')

@section('content')

    <twitter-posting-panels
            accountsjson="{{ $accounts->toJson() }}"
            clientsjson="{{ $clients->toJson() }}"
            templatesjson="{{ $templates->toJson() }}">
    </twitter-posting-panels>

@endsection