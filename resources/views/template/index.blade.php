@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">
                        List of Post Templates
                        <a href="{{ route('template.create') }}" class="btn btn-primary pull-right">
                            Create new Post Template
                        </a>
                    </h4>

                </div>
                <div class="content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th class="text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($templates as $template)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ str_limit($template->name, 30) }}</td>
                                <td>{{ str_limit($template->description, 50) }}</td>
                                <td class="text-right">
                                    <a href="{{ route('template.edit', ['template' => $template->id]) }}" class="btn btn-primary btn-xs">Edit</a>
                                    <a href="{{ route('template.destroy', ['template' => $template->id]) }}" class="btn btn-danger btn-xs"
                                       onclick="event.preventDefault(); document.getElementById('delete-template-form-{{ $template->id }}').submit();">
                                        Delete
                                    </a>

                                    <form id="delete-template-form-{{ $template->id }}" action="{{ route('template.destroy', ['template' => $template->id]) }}" method="POST">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection