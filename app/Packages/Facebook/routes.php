<?php

Route::middleware('auth')->group(function () {
    Route::resource('facebook-account', 'AccountsController');
    Route::name('facebook-groups-file')->post('facebook-account-groups', 'AccountsController@groupsUpload');
    Route::name('facebook.token')->get('facebook-account-token', 'AccountsController@token');
    Route::name('facebook.index')->get('facebook', 'PagesController@index');
    Route::name('facebook.stats')->get('facebook/stats/{queue}', 'PagesController@stats');
    Route::post('api/facebook/post', 'PostingController@post');
});