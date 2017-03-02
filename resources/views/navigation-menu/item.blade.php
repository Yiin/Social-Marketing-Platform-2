@if(empty($item->requires_permision) || $user->hasPermissionTo($item->requires_permision))

    @if(empty($item->getChilds()))

        <li class="{{ \Route::currentRouteName() === $item->route ? 'active' : '' }}">
            <a href="{{ $item->href() }}">
                <i class="{{ $item->icon }}"></i>
                <p>{{ $item->title }}</p>
            </a>
        </li>

    @else

        <li>
            <a data-toggle="collapse" href="#{{ ($hashtag = str_random()) }}">
                <i class="{{ $item->icon }}"></i>
                <p>
                    {{ $item->title }}
                    <b class="caret"></b>
                </p>
            </a>
            <div class="collapse" id="{{ $hashtag }}">
                <ul class="nav">

                    @foreach($item->getChilds() as $child)

                        @include('navigation-menu.item', ['item' => $child])

                    @endforeach

                </ul>
            </div>
        </li>

    @endif

@endif