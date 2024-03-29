<?php

namespace App\Modules\GooglePlus\Providers;

use App\Components\DashboardBlock;
use App\Constants\Permission;
use App\Modules\GooglePlus\Repositories\GoogleAccountsRepository;
use App\Modules\GooglePlus\Services\ApiService;
use App\Services\CurlService;
use App\Services\DashboardService;
use App\Services\NavigationMenuService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use nxsAPI_GP;

class GooglePlusProvider extends ServiceProvider
{
    public function boot()
    {
        $nav = $this->app->make(NavigationMenuService::class);

        $navItem = $nav->addItem('Google Plus', 'fa fa-google-plus', 'google.index', Permission::USE_ALL_SERVICES);

        $navItem->addChild('Accounts', '', 'google-account.index');
        $navItem->addChild('Posting', '', 'google.index');

        $this->addDashboardBlocks();
    }

    public function register()
    {
        // nxsAPI_GP
        $this->app->singleton(nxsAPI_GP::class, function ($app) {
            return new nxsAPI_GP;
        });

        $this->app->singleton(ApiService::class, function (Application $app) {
            return new ApiService($app->make(nxsAPI_GP::class), $app->make(CurlService::class));
        });

        $this->app->singleton(GoogleAccountsRepository::class, function (Application $app) {
            return new GoogleAccountsRepository($app->make(ApiService::class));
        });
    }

    private function addDashboardBlocks()
    {
        $dashboard = $this->app->make(DashboardService::class);

        $googleBlock = new DashboardBlock('_partials.dashboard.google-stats');

        $dashboard->addBlock($googleBlock);
    }
}