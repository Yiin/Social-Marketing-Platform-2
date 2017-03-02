@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="header">
                    <h4 class="title">
                        Users
                    </h4>
                </div>
                <div class="content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th class="text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->roles()->first()->name }}</td>
                                <td class="text-right">
                                    <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="btn btn-primary btn-xs">Edit</a>
                                    <a href="{{ route('user.destroy', ['user' => $user->id]) }}" class="btn btn-danger btn-xs"
                                       onclick="event.preventDefault(); document.getElementById('delete-user-form-{{ $user->id }}').submit();">
                                        Delete
                                    </a>

                                    <form id="delete-user-form-{{ $user->id }}" action="{{ route('user.destroy', ['user' => $user->id]) }}" method="POST">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="header">
                    <h4 class="title">Create User</h4>
                </div>
                <div class="content">
                    <form action="{{ route('user.store') }}" method="POST">
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

                        @role(App\Models\User::ROLE_ADMIN)
                        {{-- Role --}}
                        <div class="col-md-12">
                            <div class="form-group {{ $errors->has('role_id') ? 'has-error has-feedback' : '' }}">
                                <label>Role</label>
                                @foreach($roles as $role)
                                    <label class="radio">
                                        <input type="radio" data-toggle="radio" name="role_id" value="{{ $role->id }}" {{ $loop->first ? 'checked' : '' }}>
                                        {{ $role->name }}
                                    </label>
                                @endforeach

                                @if($errors->has('role_id'))
                                    @foreach($errors->get('role_id') as $error)
                                        <label class="error">{{ $error }}</label>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endrole

                        <button type="submit" class="btn btn-primary btn-fill">Create User</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection