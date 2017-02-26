<?php

namespace App\Packages\Twitter\Providers;

use App\Packages\Twitter\Repositories\TwitterAccountsRepository;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class TwitterProvider extends ServiceProvider
{
    public function boot()
    {
        $nav = $this->app->make('App\Services\NavigationMenuService');

        $navItem = $nav->addItem('Twitter', 'fa fa-twitter', 'twitter.index');

        $navItem->addChild('Accounts', '', 'twitter-account.index');
        $navItem->addChild('Tweeting', '', 'twitter.index');
    }

    public function register()
    {
        $this->app->singleton(TwitterAccountsRepository::class, function ($app) {
            return new TwitterAccountsRepository($app->make('App\Packages\Twitter\Services\ApiService'));
        });
    }
}