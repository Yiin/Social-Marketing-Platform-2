@extends ('layouts.app')

@section('content')

    <div class="card">
        <div class="header">
            <h4 class="title">List of added accounts</h4>
            <span>
                <a href="https://accounts.google.com/UnlockCaptcha" target="_blank">Click here to unlock currently signed in account.</a>
                You may need to do that in case Google blocks signing in even with correct credentials.
            </span>
        </div>
        <div class="content">
            <accounts-table data="{{ json_encode($accounts) }}"
                            sms="{{ \App\Models\SocialMediaService::all() }}"></accounts-table>
        </div>
    </div>

@endsection