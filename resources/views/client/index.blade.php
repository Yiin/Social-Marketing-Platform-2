@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="header">
                    <h4 class="title">
                        @if(isset($reseller))
                            {{ $reseller->name }}'s clients
                            <a href="{{ route('client.index') }}" class="btn btn-primary btn-xs">Show all</a>
                        @else
                            Clients
                        @endif
                    </h4>
                </div>
                <div class="content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            @can(App\Constants\Permission::MANAGE_RESELLERS)
                                <th>Reseller</th>
                            @endcan
                            <th class="text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($clients as $_client)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $_client->name }}</td>
                                <td>{{ $_client->email }}</td>
                                @can(App\Constants\Permission::MANAGE_RESELLERS)
                                    <td>
                                        @if($_client->reseller)
                                            <a href="{{ route('reseller.edit', ['reseller' => $_client->reseller_id]) }}" class="btn btn-primary btn-xs">
                                                {{ $_client->reseller->name }} ({{ $_client->reseller->email }})
                                            </a>
                                        @endif
                                    </td>
                                @endcan
                                <td class="text-right">
                                    <a href="{{ route('client.edit', ['client' => $_client->id]) }}" class="btn btn-primary btn-xs">Edit</a>
                                    <a href="{{ route('client.destroy', ['client' => $_client->id]) }}" class="btn btn-danger btn-xs"
                                       onclick="event.preventDefault(); document.getElementById('delete-client-form-{{ $_client->id }}').submit();">
                                        Delete
                                    </a>

                                    <form id="delete-client-form-{{ $_client->id }}" action="{{ route('client.destroy', ['client' => $_client->id]) }}" method="POST">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-5">
            @if(isset($client))
                <div class="card">
                    <div class="header">
                        <h4 class="title">
                            Edit Client
                        </h4>
                    </div>
                    <div class="content">
                        <form action="{{ route('client.update', ['client' => $client->id]) }}" method="POST">
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}

                            {{-- NAME --}}
                            <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                                <label>Name</label>
                                <input type="text" class="form-control" placeholder="Name" name="name"
                                       value="{{ $client->name }}">
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
                                           value="{{ $client->email }}">
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
                                           value="{{ $client->password }}" autocomplete="off">
                                    @if($errors->has('password'))
                                        @foreach($errors->get('password') as $error)
                                            <label class="error">{{ $error }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-fill">Update Client</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="header">
                        <h4 class="title">Create Client</h4>
                    </div>
                    <div class="content">
                        <form action="{{ route('client.store') }}" method="POST">
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

                            <button type="submit" class="btn btn-primary btn-fill">Create Client</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection