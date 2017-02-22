@extends ('layouts.auth')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="card">
                    <div class="header text-center">
                        Log. Posted {{ $queue->post_count }} posts,
                        ~ {{ number_format($queue->backlinks) }}
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
                                    <td>{{ $post->community_name }}</td>
                                    <td>{{ $post->message }}</td>
                                    <td>
                                        <a href="{{ $post->url }}">{{ str_limit($post->url, 40) }}</a>
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