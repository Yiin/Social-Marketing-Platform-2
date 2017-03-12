<?php

Route::middleware('auth')->group(function () {
    Route::middleware('permission:' . \App\Constants\Permission::USE_ALL_SERVICES)->group(function () {
        Route::resource('google-account', 'AccountsController');
        Route::name('google.index')->get('google', 'PagesController@index');
        Route::name('google.post')->post('api/google/post', 'PostingController@post');
    });
    Route::name('google.stats')->get('google/stats/{queue}', 'PagesController@stats');
});