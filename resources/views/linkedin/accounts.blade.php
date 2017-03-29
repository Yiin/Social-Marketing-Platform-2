@extends ('layouts.app')

@section('content')

    <div class="card">
        <div class="header">
            <h4 class="title">List of added accounts</h4>
        </div>
        <div class="content">
            <linkedin-accounts-table data="{{ json_encode($accounts) }}"></linkedin-accounts-table>
        </div>
    </div>

@endsection