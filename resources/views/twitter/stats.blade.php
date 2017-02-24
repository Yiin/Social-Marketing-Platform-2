@extends ('layouts.auth')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="card">
                    <div class="header text-center">
                        Log. Tweeted {{ $queue->tweet_count }} tweets
                    </div>
                    <div class="content">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Time</th>
                                <th>Message</th>
                                <th>URL</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($queue->tweets()->orderBy('id', 'desc')->get() as $tweet)
                                <tr>
                                    <td>{{ $tweet->created_at->diffForHumans() }}</td>
                                    <td>{{ $tweet->message }}</td>
                                    <td>
                                        <a href="{{ $tweet->url }}">{{ str_limit($tweet->url, 40) }}</a>
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