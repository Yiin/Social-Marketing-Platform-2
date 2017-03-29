<?php

Route::middleware('auth')->group(function () {
    Route::middleware('permission:' . \App\Constants\Permission::USE_ALL_SERVICES)->group(function () {
        Route::resource('linkedin-account', 'AccountsController');
        Route::name('linkedin-fetch-groups')->post('linkedin-account-groups/{linkedin_account}', 'AccountsController@fetchAndUpdateGroups');
        Route::name('linkedin.index')->get('linkedin', 'PagesController@index');
        Route::name('linkedin.post')->post('api/linkedin/post', 'PostingController@post');
    });
    Route::name('linkedin.stats')->get('linkedin/stats/{queue}', 'PagesController@stats');
});