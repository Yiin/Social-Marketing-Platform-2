@extends('layouts.app')

@section('content')

    <div class="row">
        @foreach($blocks as $block)
            @if($block->available())
                @include($block->getView(), $block->getData())
            @endif
        @endforeach
    </div>

@endsection