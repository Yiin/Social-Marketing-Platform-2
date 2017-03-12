<?php

Route::middleware('auth')->group(function () {
    Route::middleware('permission:' . \App\Constants\Permission::USE_ALL_SERVICES)->group(function () {
        Route::resource('twitter-account', 'AccountsController');
        Route::name('twitter.token')->get('twitter-account-token', 'AccountsController@token');
        Route::name('twitter.index')->get('twitter', 'PagesController@index');
        Route::name('twitter.post')->post('api/twitter/post', 'PostingController@post');
    });
    Route::name('twitter.stats')->get('twitter/stats/{queue}', 'PagesController@stats');
});