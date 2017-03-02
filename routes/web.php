<?php

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::name('dashboard')->get('/', 'DashboardController@index');
    Route::name('profile')->get('my-profile', 'DashboardController@profile');

    Route::middleware('permission:' . App\Models\User::MANAGE_RESELLERS)->group(function () {
        Route::resource('reseller', 'ResellersController');
        Route::name('reseller.clients')->get('reseller-clients/{reseller}', 'ResellersController@clients');
    });
    Route::middleware('permission:' . App\Models\User::MANAGE_CLIENTS)->group(function () {
        Route::resource('client', 'ClientsController');
    });

    Route::resource('user', 'UserController');
    Route::name('user.change-password')->patch('change-password/{user}', 'UserController@changePassword');

    Route::resource('template', 'TemplateController');
});