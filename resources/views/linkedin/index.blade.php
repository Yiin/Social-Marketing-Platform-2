@extends('layouts.app')

@section('content')

    <linkedin-posting-panels
            accountsjson="{{ $accounts->toJson() }}"
            clientsjson="{{ $clients->toJson() }}"
            templatesjson="{{ $templates->toJson() }}">
    </linkedin-posting-panels>

@endsection