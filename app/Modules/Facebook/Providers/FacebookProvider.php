<?php

namespace App\Modules\Facebook\Providers;

use App\Components\DashboardBlock;
use App\Constants\Permission;
use App\Modules\Facebook\Models\FacebookQueue;
use App\Modules\Facebook\Repositories\FacebookAccountsRepository;
use App\Modules\Facebook\Services\ApiService;
use App\Services\DashboardService;
use App\Services\NavigationMenuService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class FacebookProvider extends ServiceProvider
{
    public function boot()
    {
        $this->addNavMenuItems();
        $this->addDashboardBlocks();
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

    private function addNavMenuItems()
    {
        $nav = $this->app->make(NavigationMenuService::class);

        $navItem = $nav->addItem('Facebook', 'fa fa-facebook', 'facebook.index', Permission::USE_ALL_SERVICES);

        $navItem->addChild('Accounts', '', 'facebook-account.index');
        $navItem->addChild('Posting', '', 'facebook.index');
    }

    private function addDashboardBlocks()
    {
        $dashboard = $this->app->make(DashboardService::class);

        $errorLogBlock = new DashboardBlock('_partials.dashboard.facebook-stats');

        $dashboard->addBlock($errorLogBlock);
    }
}