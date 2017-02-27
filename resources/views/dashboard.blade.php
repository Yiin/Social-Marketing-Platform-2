@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="header">
                    <h4 class="title">Errors Log</h4>
                    <p class="category">A list of logged errors with most recent ones at the top.</p>
                </div>
                <div class="content table-responsive table-full-width">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Error Message</th>
                            <th>Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(($paginated = \App\Models\ErrorLog::orderBy('id', 'desc')->paginate(10)) as $error)
                            <tr>
                                <td class="text-center">{{ $error->id }}</td>
                                <td>{{ $error->message }}</td>
                                <td>{{ $error->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $paginated->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection