<?php

namespace App\Modules\Twitter\Providers;

use App\Components\DashboardBlock;
use App\Constants\Permission;
use App\Modules\Twitter\Repositories\TwitterAccountsRepository;
use App\Modules\Twitter\Services\ApiService;
use App\Services\DashboardService;
use App\Services\NavigationMenuService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class TwitterProvider extends ServiceProvider
{
    public function boot()
    {
        $nav = $this->app->make(NavigationMenuService::class);

        $navItem = $nav->addItem('Twitter', 'fa fa-twitter', 'twitter.index', Permission::USE_ALL_SERVICES);

        $navItem->addChild('Accounts', '', 'twitter-account.index');
        $navItem->addChild('Tweeting', '', 'twitter.index');

        $this->addDashboardBlocks();
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

    private function addDashboardBlocks()
    {
        $dashboard = $this->app->make(DashboardService::class);

        $twitterBlock = new DashboardBlock('_partials.dashboard.twitter-stats');

        $dashboard->addBlock($twitterBlock);
    }
}