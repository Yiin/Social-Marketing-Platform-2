<?php

namespace App\Providers;

use App\Models\User;
use App\Services\CurlService;
use App\Services\DashboardService;
use App\Services\NavigationMenuService;
use Auth;
use Illuminate\Support\ServiceProvider;
use nxsAPI_GP;
use Schema;
use Validator;

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

        Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
            return \Hash::check($value, $parameters[0]);
        });
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

        $this->app->singleton(DashboardService::class, function ($app) {
            return new DashboardService;
        });
    }
}
