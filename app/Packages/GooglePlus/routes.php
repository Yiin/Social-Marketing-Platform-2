<?php

Route::middleware(['auth', 'permission:' . App\Models\User::USE_ALL_SERVICES])->group(function () {
    Route::resource('google-account', 'AccountsController');
    Route::name('google.index')->get('google', 'PagesController@index');
    Route::name('google.stats')->get('google/stats/{queue}', 'PagesController@stats');
    Route::post('api/google/post', 'PostingController@post');
});