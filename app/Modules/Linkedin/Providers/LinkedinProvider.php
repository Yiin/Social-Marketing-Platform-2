<?php

namespace App\Modules\Linkedin\Providers;

use App\Components\DashboardBlock;
use App\Constants\Permission;
use App\Modules\Linkedin\Models\LinkedinQueue;
use App\Modules\Linkedin\Repositories\LinkedinAccountsRepository;
use App\Modules\Linkedin\Services\ApiService;
use App\Services\DashboardService;
use App\Services\NavigationMenuService;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class LinkedinProvider extends ServiceProvider
{
    public function boot()
    {
        $this->addNavMenuItems();
        $this->addDashboardBlocks();
    }

    public function register()
    {
        $this->app->singleton(ApiService::class, function (Application $app) {
            return new ApiService($app->make(HttpClient::class));
        });

        $this->app->singleton(LinkedinAccountsRepository::class, function (Application $app) {
            return new LinkedinAccountsRepository($app->make(ApiService::class));
        });
    }

    private function addNavMenuItems()
    {
        $nav = $this->app->make(NavigationMenuService::class);

        $navItem = $nav->addItem('LinkedIn', 'fa fa-linkedin', 'linkedin.index', Permission::USE_ALL_SERVICES);

        $navItem->addChild('Accounts', '', 'linkedin-account.index');
        $navItem->addChild('Posting', '', 'linkedin.index');
    }

    private function addDashboardBlocks()
    {
        $dashboard = $this->app->make(DashboardService::class);

        $linkedinBlock = new DashboardBlock('_partials.dashboard.linkedin-stats');

        $dashboard->addBlock($linkedinBlock);
    }
}