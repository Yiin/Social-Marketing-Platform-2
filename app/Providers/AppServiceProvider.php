<?php

namespace App\Providers;

use App\Services\CurlService;
use App\Services\FacebookPagesService;
use App\Services\GooglePlusService;
use App\Services\LinkedInService;
use App\Services\QueueService;
use App\Services\UserService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use nxsAPI_FP;
use nxsAPI_GP;
use nxsAPI_LI;
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
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // HTTP Requests
        $this->app->singleton('App\Service\CurlService', function ($app) {
            return new CurlService;
        });

        // nxsAPI_GP
        $this->app->singleton('nxsAPI_GP', function ($app) {
            return new nxsAPI_GP;
        });

        // GooglePlusService
        $this->app->singleton('App\Service\GooglePlusService', function (Application $app) {
            return new GooglePlusService($app->make('nxsAPI_GP'), $app->make('App\Service\CurlService'));
        });
    }
}
