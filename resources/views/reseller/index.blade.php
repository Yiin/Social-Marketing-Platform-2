@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="header">
                    <h4 class="title">
                        Resellers
                    </h4>
                </div>
                <div class="content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($resellers as $_reseller)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $_reseller->name }}</td>
                                <td>{{ $_reseller->email }}</td>
                                <td class="text-right">
                                    <a href="{{ route('reseller.clients', ['reseller' => $_reseller->id]) }}" class="btn btn-primary btn-xs">View Clients</a>
                                    <a href="{{ route('reseller.edit', ['reseller' => $_reseller->id]) }}" class="btn btn-primary btn-xs">Edit</a>
                                    <a href="{{ route('reseller.destroy', ['reseller' => $_reseller->id]) }}" class="btn btn-danger btn-xs"
                                       onclick="event.preventDefault(); document.getElementById('delete-reseller-form-{{ $_reseller->id }}').submit();">
                                        Delete
                                    </a>

                                    <form id="delete-reseller-form-{{ $_reseller->id }}" action="{{ route('reseller.destroy', ['reseller' => $_reseller->id]) }}" method="POST">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $resellers->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-5">
            @if(isset($reseller))
                <div class="card">
                    <div class="header">
                        <h4 class="title">
                            Edit Reseller
                        </h4>
                    </div>
                    <div class="content">
                        <form action="{{ route('reseller.update', ['reseller' => $reseller->id]) }}" method="POST">
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}

                            {{-- NAME --}}
                            <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                                <label>Name</label>
                                <input type="text" class="form-control" placeholder="Name" name="name"
                                       value="{{ $reseller->name }}">
                                @if($errors->has('name'))
                                    @foreach($errors->get('name') as $error)
                                        <label class="error">{{ $error }}</label>
                                    @endforeach
                                @endif
                            </div>
                            {{-- Email --}}
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
                                    <label>Email</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email"
                                           value="{{ $reseller->email }}">
                                    @if($errors->has('email'))
                                        @foreach($errors->get('email') as $error)
                                            <label class="error">{{ $error }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            {{-- Password --}}
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
                                    <label>New password (optional)</label>
                                    <input type="password" class="form-control" placeholder="New Password" name="password"
                                           value="{{ $reseller->password }}" autocomplete="off">
                                    @if($errors->has('password'))
                                        @foreach($errors->get('password') as $error)
                                            <label class="error">{{ $error }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-fill">Update Reseller</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="header">
                        <h4 class="title">Create Reseller</h4>
                    </div>
                    <div class="content">
                        <form action="{{ route('reseller.store') }}" method="POST">
                            {{ csrf_field() }}

                            {{-- NAME --}}
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
                            {{-- Email --}}
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
                                    <label>Email</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email"
                                           value="{{ old('email') }}">
                                    @if($errors->has('email'))
                                        @foreach($errors->get('email') as $error)
                                            <label class="error">{{ $error }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            {{-- Password --}}
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
                                    <label>Password (optional, can be generated automatically)</label>
                                    <input type="password" class="form-control" placeholder="New Password" name="password"
                                           value="{{ old('password') }}" autocomplete="off">
                                    @if($errors->has('password'))
                                        @foreach($errors->get('password') as $error)
                                            <label class="error">{{ $error }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-fill">Create Reseller</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection