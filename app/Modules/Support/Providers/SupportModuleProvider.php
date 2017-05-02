<?php

namespace App\Modules\Support\Providers;

use App\Components\DashboardBlock;
use App\Constants\Permission;
use App\Services\DashboardService;
use App\Services\NavigationMenuService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use nxsAPI_GP;

class SupportModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $nav = $this->app->make(NavigationMenuService::class);

        $navItem = $nav->addItem('Support', 'fa fa-question', 'support.index');


        $this->addDashboardBlocks();
    }

    private function addDashboardBlocks()
    {
        $dashboard = $this->app->make(DashboardService::class);

        $googleBlock = new DashboardBlock('_partials.dashboard.google-stats');

        $dashboard->addBlock($googleBlock);
    }
}