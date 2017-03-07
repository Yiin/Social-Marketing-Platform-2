<?php

namespace App\Modules\Errors\Providers;

use App\Components\DashboardBlock;
use App\Modules\Errors\Models\ErrorLog;
use App\Services\DashboardService;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class ErrorLogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $dashboard = $this->app->make(DashboardService::class);

        $errorLogBlock = new DashboardBlock('_partials.dashboard.errors-log', [
            'errorsLog' => ErrorLog::orderBy('id', 'desc')->paginate(8)
        ]);

        $dashboard->addBlock($errorLogBlock);
    }

    public function register()
    {

    }
}