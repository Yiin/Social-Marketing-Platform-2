<?php

Auth::routes();

Route::get('linkedin/groups', function () {
    $client = new \GuzzleHttp\Client();
    $res    = $client->request('POST', 'http://127.0.0.1:3000/groups', [
        'json' => [
            'username' => 'stanislovas.janonis@gmail.com',
            'password' => '5284691367',
        ],
    ]);
    dd((string) $res->getBody());
});

Route::post('linkedin/groups', function (\Illuminate\Http\Request $request) {
    return response('Output: ' . $request->get('output'));
});

Route::middleware('auth')->group(function () {
    Route::name('dashboard')->get('/', 'DashboardController@index');
    Route::name('profile')->get('my-profile', 'DashboardController@profile');

    Route::middleware('permission:' . App\Constants\Permission::MANAGE_RESELLERS)->group(function () {
        Route::resource('reseller', 'ResellersController');
        Route::name('reseller.clients')->get('reseller-clients/{reseller}', 'ResellersController@clients');
    });

    Route::middleware('permission:' . App\Constants\Permission::MANAGE_CLIENTS)->group(function () {
        Route::resource('client', 'ClientsController');
    });

    Route::resource('user', 'UserController');
    Route::name('user.change-password')->patch('change-password/{user}', 'UserController@changePassword');

    Route::resource('template', 'TemplateController');
});
