<?php

namespace App\Packages\Twitter\Providers;

use App\Models\User;
use App\Packages\Twitter\Repositories\TwitterAccountsRepository;
use App\Packages\Twitter\Services\ApiService;
use App\Services\NavigationMenuService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class TwitterProvider extends ServiceProvider
{
    public function boot()
    {
        $nav = $this->app->make(NavigationMenuService::class);

        $navItem = $nav->addItem('Twitter', 'fa fa-twitter', 'twitter.index', User::USE_ALL_SERVICES);

        $navItem->addChild('Accounts', '', 'twitter-account.index');
        $navItem->addChild('Tweeting', '', 'twitter.index');
    }

    public function register()
    {
        $this->app->singleton(ApiService::class, function () {
            return new ApiService;
        });

        $this->app->singleton(TwitterAccountsRepository::class, function (Application $app) {
            return new TwitterAccountsRepository($app->make(ApiService::class));
        });
    }
}