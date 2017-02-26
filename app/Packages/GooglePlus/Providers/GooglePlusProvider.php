<?php

namespace App\Packages\GooglePlus\Providers;

use App\Packages\GooglePlus\Repositories\GoogleAccountsRepository;
use App\Packages\GooglePlus\Services\ApiService;
use App\Services\CurlService;
use App\Services\NavigationMenuService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use nxsAPI_GP;

class GooglePlusProvider extends ServiceProvider
{
    public function boot()
    {
        $nav = $this->app->make(NavigationMenuService::class);

        $navItem = $nav->addItem('Google Plus', 'fa fa-google-plus', 'google.index');

        $navItem->addChild('Accounts', '', 'google-account.index');
        $navItem->addChild('Posting', '', 'google.index');
    }

    public function register()
    {
        $this->app->singleton(ApiService::class, function (Application $app) {
            return new ApiService($app->make(nxsAPI_GP::class), $app->make(CurlService::class));
        });

        $this->app->singleton(GoogleAccountsRepository::class, function (Application $app) {
            return new GoogleAccountsRepository($app->make(ApiService::class));
        });
    }
}