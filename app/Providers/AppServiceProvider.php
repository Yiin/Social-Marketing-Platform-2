<?php

namespace App\Providers;

use App\Services\CurlService;
use App\Services\NavigationMenuService;
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

        $nav = $this->app->make('App\Services\NavigationMenuService');

        foreach (config('app.routes') as $item) {
            $nav->addItem($item['title'], $item['icon'], $item['route']);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // HTTP Requests
        $this->app->singleton(CurlService::class, function ($app) {
            return new CurlService;
        });

        // nxsAPI_GP
        $this->app->singleton(nxsAPI_GP::class, function ($app) {
            return new nxsAPI_GP;
        });

        $this->app->singleton(NavigationMenuService::class, function ($app) {
            return new NavigationMenuService;
        });
    }
}
