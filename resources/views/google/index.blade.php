@extends('layouts.app')

@section('content')

    <google-plus-posting-panels
            accountsjson="{{ $accounts->toJson() }}"
            clientsjson="{{ $clients->toJson() }}"
            templatesjson="{{ $templates->toJson() }}">
    </google-plus-posting-panels>

@endsection