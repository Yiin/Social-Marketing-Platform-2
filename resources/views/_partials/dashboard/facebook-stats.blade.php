<div class="col-md-{{ $width or 4 }}">
    <div class="card">
        <div class="header">
            <h4 class="title"><i class="fa fa-facebook"></i> Facebook Stats</h4>
            <p class="category">A list of recent facebook posting queues.</p>
        </div>
        @role(App\Constants\Role::ADMIN)
        <?php $facebookStats = App\Modules\Facebook\Models\FacebookQueue::orderBy('id', 'desc')->paginate(8); ?>
        @endrole
        @role(App\Constants\Role::RESELLER)
        <?php $facebookStats = $user->facebookQueues()->orderBy('id', 'desc')->paginate(8); ?>
        @endrole
        @role(App\Constants\Role::CLIENT)
        <?php $facebookStats = App\Modules\Facebook\Models\FacebookQueue::orderBy('id', 'desc')->where('client_id', $user->id)->paginate(8); ?>
        @endrole

        @if($facebookStats->count())
            <div class="content table-responsive table-full-width">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="text-center">Posts</th>
                        <th class="text-center">Backlinks</th>
                        <th class="text-center">Client</th>
                        <th class="text-center">Tasks left</th>
                        <th class="text-center">Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($facebookStats as $queue)
                        <tr>
                            <td class="text-center">{{ number_format($queue->post_count) }}</td>
                            <td class="text-center">{{ number_format($queue->backlinks) }}</td>
                            <td class="text-center">{{ $queue->client ? $queue->client->name : '' }}</td>
                            <td class="text-center">
                                @if($queue->jobs > 0)
                                    {{ number_format($queue->jobs) }}
                                @else
                                    <a href="{{ route('facebook.stats', ['queue' => $queue->id]) }}" target="_blank">
                                        Completed
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">{{ $queue->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $facebookStats->links() }}
            </div>
        @else
            <div class="content">
                Nothing to see here.

                @can(App\Constants\Permission::USE_ALL_SERVICES)
                    <a href="{{ route('facebook.index') }}">Post something!</a>
                @endcan
            </div>
        @endif
    </div>
</div>