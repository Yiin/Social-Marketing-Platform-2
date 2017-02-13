@extends ('layouts.auth')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="card">
                    <div class="header text-center">
                        Log. Posted {{ $queue->stats['posts'] }} posts,
                        ~ {{ number_format($queue->stats['backlinks']) }}
                        backlinks
                    </div>
                    <div class="content">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Time</th>
                                <th>Group Name</th>
                                <th>Message</th>
                                <th>URL</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($queue->posts()->orderBy('id', 'desc')->get() as $post)
                                <tr>
                                    <td>{{ $post->created_at->diffForHumans() }}</td>
                                    <td>{{ $post->data['groupName'] }}</td>
                                    <td>{{ $post->data['message'] }}</td>
                                    <td>
                                        <a href="{{ $post->data['url'] }}">{{ str_limit($post->data['url'], 40) }}</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection