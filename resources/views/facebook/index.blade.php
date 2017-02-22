@extends('layouts.app')

@section('content')

    <facebook-posting-panels
            accountsjson="{{ $accounts->toJson() }}"
            clientsjson="{{ $clients->toJson() }}"
            templatesjson="{{ $templates->toJson() }}">
    </facebook-posting-panels>

@endsection