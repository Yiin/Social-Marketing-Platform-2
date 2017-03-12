<div class="col-md-{{ $width or 4 }}">
    <div class="card">
        <div class="header">
            <h4 class="title"><i class="fa fa-google-plus"></i> Google Stats</h4>
            <p class="category">A list of recent google posting queues.</p>
        </div>
        @role(App\Constants\Role::ADMIN)
        <?php $googleStats = App\Modules\GooglePlus\Models\GoogleQueue::orderBy('id', 'desc')->paginate(8); ?>
        @endrole
        @role(App\Constants\Role::RESELLER)
        <?php $googleStats = $user->googleQueues()->orderBy('id', 'desc')->paginate(8); ?>
        @endrole
        @role(App\Constants\Role::CLIENT)
        <?php $googleStats = App\Modules\GooglePlus\Models\GoogleQueue::orderBy('id', 'desc')->where('client_id', $user->id)->paginate(8); ?>
        @endrole

        @if($googleStats->count())
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
                    @foreach($googleStats as $queue)
                        <tr>
                            <td class="text-center">{{ number_format($queue->post_count) }}</td>
                            <td class="text-center">{{ number_format($queue->backlinks) }}</td>
                            <td class="text-center">{{ $queue->client ? $queue->client->name : '' }}</td>
                            <td class="text-center">
                                @if($queue->jobs)
                                    {{ number_format($queue->jobs) }}
                                @else
                                    <a href="{{ route('google.stats', ['queue' => $queue->id]) }}" target="_blank">
                                        Completed
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">{{ $queue->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $googleStats->links() }}
            </div>
        @else
            <div class="content">
                Nothing to see here.

                @can(App\Constants\Permission::USE_ALL_SERVICES)
                    <a href="{{ route('google-plus.index') }}">Post something!</a>
                @endcan
            </div>
        @endif
    </div>
</div>