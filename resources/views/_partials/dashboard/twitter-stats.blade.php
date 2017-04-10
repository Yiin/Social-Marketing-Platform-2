<div class="col-md-{{ $width or 4 }}">
    <div class="card">
        <div class="header">
            <h4 class="title"><i class="fa fa-twitter"></i> Twitter Stats</h4>
            <p class="category">A list of recent twitter posting queues.</p>
        </div>
        @role(App\Constants\Role::ADMIN)
        <?php $twitterStats = App\Modules\Twitter\Models\TwitterQueue::orderBy('id', 'desc')->paginate(8); ?>
        @endrole
        @role(App\Constants\Role::RESELLER)
        <?php $twitterStats = $user->twitterQueues()->orderBy('id', 'desc')->paginate(8); ?>
        @endrole
        @role(App\Constants\Role::CLIENT)
        <?php $twitterStats = App\Modules\Twitter\Models\TwitterQueue::orderBy('id', 'desc')->where('client_id', $user->id)->paginate(8); ?>
        @endrole

        @if($twitterStats->count())
            <div class="content table-responsive table-full-width">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="text-center">Tweets</th>
                        <th class="text-center">Client</th>
                        <th class="text-center">Tasks left</th>
                        <th class="text-center">Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($twitterStats as $queue)
                        <tr>
                            <td class="text-center">{{ number_format($queue->tweet_count) }}</td>
                            <td class="text-center">{{ $queue->client ? $queue->client->name : '' }}</td>
                            <td class="text-center">
                                @if($queue->jobs > 0)
                                    {{ number_format($queue->jobs) }}
                                @else
                                    <a href="{{ route('twitter.stats', ['queue' => $queue->id]) }}" target="_blank">
                                        Completed
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">{{ $queue->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $twitterStats->links() }}
            </div>
        @else
            <div class="content">
                Nothing to see here.

                @can(App\Constants\Permission::USE_ALL_SERVICES)
                    <a href="{{ route('twitter.index') }}">Post something!</a>
                @endcan
            </div>
        @endif
    </div>
</div>