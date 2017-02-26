<?php

namespace App\Packages\GooglePlus\Providers;


use App\Packages\GooglePlus\Repositories\GoogleAccountsRepository;
use App\Packages\GooglePlus\Services\ApiService;
use Illuminate\Support\ServiceProvider;

class GooglePlusProvider extends ServiceProvider
{
    public function boot()
    {
        $nav = $this->app->make('App\Services\NavigationMenuService');

        $navItem = $nav->addItem('Google Plus', 'fa fa-google-plus', 'google.index');

        $navItem->addChild('Accounts', '', 'google-account.index');
        $navItem->addChild('Posting', '', 'google.index');
    }

    public function register()
    {
        // nxsAPI_GP
        $this->app->singleton(nxsAPI_GP::class, function ($app) {
            return new nxsAPI_GP;
        });

        $this->app->singleton(ApiService::class, function ($app) {
            return new ApiService($app->make('nxsAPI_GP'), $app->make('App\Services\CurlService'));
        });

        $this->app->singleton(GoogleAccountsRepository::class, function ($app) {
            return new GoogleAccountsRepository($app->make('App\Packages\GooglePlus\Services\ApiService'));
        });
    }
}