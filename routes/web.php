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

    Route::name('facebook-groups')->get('facebook-groups', function () {
        //
    });

    Route::post('api/post/{socialMediaService}', 'PostingController@post');

    Route::name('social-media-service')->get('social-media-service/{socialMediaService}', 'PostingController@view');
    Route::name('account-login-callback')->get('account-login-callback/{socialMediaService}/{account?}', 'AccountController@accessToken');
});

Route::name('stats')->get('stats/{queue}', function (\App\Models\Queue $queue) {
    return view('stats')->with(compact('queue'));
});

Route::get('fb/{account}', function (\App\Packages\Facebook\Models\FacebookAccount $account) {
    $facebook = new Facebook($config = [
        'app_id' => env('FACEBOOK_APP_ID'),
        'app_secret' => env('FACEBOOK_APP_SECRET')
    ]);

    $helper = $facebook->getRedirectLoginHelper();

    $loginUrl = $helper->getLoginUrl(URL::to(route('c', [
        'socialMediaService' => $account->social_media_service_id,
        'account' => $account->id
    ])), [
        'publish_actions,publish_pages,manage_pages'
    ]);

    return redirect($loginUrl);
});

Route::name('c')->get('c', function (\Illuminate\Http\Request $request) {
    $facebook = new Facebook($config = [
        'app_id' => env('FACEBOOK_APP_ID'),
        'app_secret' => env('FACEBOOK_APP_SECRET')
    ]);

    $helper = $facebook->getRedirectLoginHelper();

    try {
        $accessToken = $helper->getAccessToken();
    } catch (FacebookSDKException $e) {
        return response()->json(['error' => $e->getMessage()], 401);
    }

    $facebook->setDefaultAccessToken($accessToken);

    $data = [
        'link' => 'http://www.maze.lt',
        'name' => 'NAME_PARAMETER',
        'picture' => 'https://images.discordapp.net/eyJ1cmwiOiJodHRwczovL2Rpc2NvcmQuc3RvcmFnZS5nb29nbGVhcGlzLmNvbS9hdHRhY2htZW50cy8xNDg3Nzg2NTM1MTYzMDAyODgvMjgyNTAwODk4OTM1MTQ0NDQ4L0MxVU94ZHhYVUFFVmlUMS5qcGcifQ.2750Khj5BRy8Sf8MZJXg7QLOtV8?width=364&height=485',
        'description' => 'DESCRIPTION_PARAMETER',
        'message' => 'MESSAGE_PARAMETER',
        'caption' => 'CAPTION_PARAMETER'
    ];

    try {
        $r = $facebook->post("/1669443953071578/feed", $data, $accessToken);//->getBody();
    } catch (FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    echo json_decode($r->getBody())->id;

    //    $response = $facebook->get('/me?fields=name', $account->access_token)->getBody();
//    $me = json_decode($response);

//    $response = $facebook->get('/100001523061180/feed', $account->access_token)->getBody();
//    dd($response);

//    $post_id = $post_id['id'];
//    echo $post_id . "<br>";
//    if ($post_id == '') {
//        echo $post;
//    }
//    $group_sting = explode('_', $post_id);
//    $group_id = $group_sting[0];
//    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
//    curl_close($curl);
//
//    dd($group_id);
});