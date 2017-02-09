@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Create Post Template</h4>
                </div>
                <div class="content">
                    <form action="{{ route('template.store') }}" method="POST">
                        {{ method_field('POST') }}
                        {{ csrf_field() }}

                        <div class="row">
                            {{-- NAME --}}
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                                    <label>Name</label>
                                    <input type="text" class="form-control" placeholder="Name" name="name"
                                           value="{{ old('name') }}">
                                    @if($errors->has('name'))
                                        @foreach($errors->get('name') as $error)
                                            <label class="error">{{ $error }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            {{-- CAPTION --}}
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('caption') ? 'has-error has-feedback' : '' }}">
                                    <label>Caption</label>
                                    <input type="text" class="form-control" placeholder="Caption" name="caption"
                                           value="{{ old('caption') }}">
                                    @if($errors->has('caption'))
                                        @foreach($errors->get('caption') as $error)
                                            <label class="error">{{ $error }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- URL --}}
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('url') ? 'has-error has-feedback' : '' }}">
                                    <label>URL</label>
                                    <input type="text" class="form-control" placeholder="URL" name="url"
                                           value="{{ old('url') }}">
                                    @if($errors->has('url'))
                                        @foreach($errors->get('url') as $error)
                                            <label class="error">{{ $error }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            {{-- IMAGE_URL --}}
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('image_url') ? 'has-error has-feedback' : '' }}">
                                    <label>Image URL</label>
                                    <input type="text" class="form-control" placeholder="Image URL" name="image_url"
                                           value="{{ old('image_url') }}">
                                    @if($errors->has('image_url'))
                                        @foreach($errors->get('image_url') as $error)
                                            <label class="error">{{ $error }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- DESCRIPTION --}}
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('description') ? 'has-error has-feedback' : '' }}">
                                    <label>Description</label>
                                    <textarea class="form-control" placeholder="Description" name="description" cols="30" rows="5">{{ old('description') }}</textarea>
                                    @if($errors->has('description'))
                                        @foreach($errors->get('description') as $error)
                                            <label class="error">{{ $error }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            {{-- MESSAGE --}}
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('message') ? 'has-error has-feedback' : '' }}">
                                    <label>Message</label>
                                    <textarea class="form-control" placeholder="Message" name="message" cols="30" rows="5">{{ old('message') }}</textarea>
                                    @if($errors->has('message'))
                                        @foreach($errors->get('message') as $error)
                                            <label class="error">{{ $error }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-fill">Create post template</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection