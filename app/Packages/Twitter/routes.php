<?php

Route::middleware('auth')->group(function () {
    Route::resource('twitter-account', 'AccountsController');
    Route::name('twitter.token')->get('twitter-account-token', 'AccountsController@token');
    Route::name('twitter.index')->get('twitter', 'PagesController@index');
    Route::name('twitter.stats')->get('twitter/stats/{queue}', 'PagesController@stats');
    Route::post('api/twitter/post', 'PostingController@post');
});