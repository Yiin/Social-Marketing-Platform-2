<?php

namespace App\Packages\Facebook\Providers;

use App\Models\User;
use App\Packages\Facebook\Repositories\FacebookAccountsRepository;
use App\Packages\Facebook\Services\ApiService;
use App\Services\NavigationMenuService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class FacebookProvider extends ServiceProvider
{
    public function boot()
    {
        $nav = $this->app->make(NavigationMenuService::class);

        $navItem = $nav->addItem('Facebook', 'fa fa-facebook', 'facebook.index', User::USE_ALL_SERVICES);

        $navItem->addChild('Accounts', '', 'facebook-account.index');
        $navItem->addChild('Posting', '', 'facebook.index');
    }

    public function register()
    {
        $this->app->singleton(ApiService::class, function () {
            return new ApiService;
        });

        $this->app->singleton(FacebookAccountsRepository::class, function (Application $app) {
            return new FacebookAccountsRepository($app->make(ApiService::class));
        });
    }
}