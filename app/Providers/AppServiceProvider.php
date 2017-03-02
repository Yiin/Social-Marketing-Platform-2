<?php

namespace App\Providers;

use App\Models\User;
use App\Services\CurlService;
use App\Services\NavigationMenuService;
use Auth;
use Illuminate\Support\ServiceProvider;
use nxsAPI_GP;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
            return \Hash::check($value, $parameters[0]);
        });

        $nav = $this->app->make(NavigationMenuService::class);

        $nav->addItem('Dashboard', 'pe-7s-graph', 'dashboard');
        $nav->addItem('My Profile', 'pe-7s-user', 'profile');

        $nav->addItem('Resellers', 'pe-7s-users', 'reseller.index', User::MANAGE_RESELLERS);
        $nav->addItem('Clients', 'pe-7s-users', 'client.index', User::MANAGE_CLIENTS);

        $nav->addItem('Templates', 'pe-7s-note2', 'template.index', User::MANAGE_CLIENTS);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // HTTP Requests
        $this->app->singleton(CurlService::class, function () {
            return new CurlService;
        });

        $this->app->singleton(NavigationMenuService::class, function ($app) {
            return new NavigationMenuService;
        });
    }
}
