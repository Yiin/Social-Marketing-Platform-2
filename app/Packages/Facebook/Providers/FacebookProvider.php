<?php
/**
 * Created by PhpStorm.
 * User: Stanislovas
 * Date: 2017-02-22
 * Time: 12:41
 */

namespace App\Packages\Facebook\Providers;

use App\Packages\Facebook\Repositories\FacebookAccountsRepository;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class FacebookProvider extends ServiceProvider
{
    public function boot()
    {
        $nav = $this->app->make('App\Services\NavigationMenuService');

        $navItem = $nav->addItem('Facebook', 'fa fa-facebook', 'facebook.index');

        $navItem->addChild('Accounts', '', 'facebook-account.index');
        $navItem->addChild('Posting', '', 'facebook.index');
    }

    public function register()
    {
        $this->app->singleton(FacebookAccountsRepository::class, function ($app) {
            return new FacebookAccountsRepository($app->make('App\Packages\Facebook\Services\ApiService'));
        });
    }
}