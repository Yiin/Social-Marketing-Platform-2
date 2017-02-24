<?php


use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use App\Vendor\Facebook\Facebook;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::name('dashboard')->get('/', 'DashboardController@index');
    Route::name('profile')->get('my-profile', 'DashboardController@profile');

    Route::resource('user', 'UserController');
    Route::name('user.change-password')->patch('change-password/{user}', 'UserController@changePassword');

    Route::resource('client', 'ClientController');
    Route::resource('template', 'TemplateController');
});

Route::get('t', function () {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $request_token = $connection->getRequestToken(URL::to(route('tt')));

    //Received token info from twitter
    session()->put('token', $request_token['oauth_token']);
    session()->put('token_secret', $request_token['oauth_token_secret']);

    //Any value other than 200 is failure, so continue only if http code is 200
    if ($connection->http_code == '200') {
        //redirect user to twitter
        $twitter_url = "https://api.twitter.com/oauth/authorize?oauth_token=" . $request_token['oauth_token'];

        return redirect($twitter_url);
    }
    return response('fail');
});

Route::name('tt')->get('tt', function (\Illuminate\Http\Request $request) {
    //Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, session('token'), session('token_secret'));
    $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
    if ($connection->http_code == '200') {
        $access_token['oauth_token'];
        $access_token['oauth_token_secret'];
        $access_token['user_id'];
        $access_token['screen_name'];

        //Insert user into the database
        $tweet = $connection->post('statuses/update', array('status' => "TEST TEST 123"));

        if (isset($tweet->errors)) {
            return response('error');
        }

        $screenName = $tweet->user->screen_name;
        $postId = $tweet->id;

        return redirect("https://twitter.com/$screenName/status/$postId");


//        $accounts[] = array("token" => (string)$access_token['oauth_token'], "token_secret" => (string)$access_token['oauth_token_secret'], "name" => $access_token['screen_name']);
//        $json = json_encode($accounts);
    }
});