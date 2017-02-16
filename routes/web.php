<?php

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::name('dashboard')->get('/', 'DashboardController@index');
    Route::name('profile')->get('my-profile', 'DashboardController@profile');

    Route::resource('user', 'UserController');
    Route::name('user.change-password')->patch('change-password/{user}', 'UserController@changePassword');

    Route::resource('client', 'ClientController');
    Route::resource('template', 'TemplateController');
    Route::resource('account', 'AccountController');

    Route::post('api/post/{socialMediaService}', 'PostingController@post');

    Route::name('social-media-service')->get('social-media-service/{socialMediaService}', 'PostingController@view');
});

Route::name('stats')->get('stats/{queue}', function (\App\Models\Queue $queue) {
    return view('stats')->with(compact('queue'));
});

Route::get('q', function () {
    //
});