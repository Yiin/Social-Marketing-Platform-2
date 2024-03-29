<?php

Route::middleware('auth')->group(function () {
    Route::middleware('permission:' . \App\Constants\Permission::USE_ALL_SERVICES)->group(function () {
        Route::resource('facebook-account', 'AccountsController');
        Route::name('facebook-groups-file')->post('facebook-account-groups', 'AccountsController@groupsUpload');
        Route::name('facebook.token')->get('facebook-account-token', 'AccountsController@token');
        Route::name('facebook.index')->get('facebook', 'PagesController@index');
        Route::name('facebook.post')->post('api/facebook/post', 'PostingController@post');
    });
    Route::name('facebook.stats')->get('facebook/stats/{queue}', 'PagesController@stats');
});