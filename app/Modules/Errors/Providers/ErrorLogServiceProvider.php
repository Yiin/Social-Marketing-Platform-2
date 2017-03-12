<?php

namespace App\Modules\Errors\Providers;

use App\Components\DashboardBlock;
use App\Constants\Permission;
use App\Modules\Errors\Models\ErrorLog;
use App\Services\DashboardService;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class ErrorLogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (config('dashboard.error-log')) {
            $dashboard = $this->app->make(DashboardService::class);

            $errorLogBlock = new DashboardBlock('_partials.dashboard.errors-log', [
                'errorsLog' => ErrorLog::orderBy('id', 'desc')->paginate(8)
            ]);
            $errorLogBlock->requiresPermission(Permission::VIEW_ERRORS_LOG);

            $dashboard->addBlock($errorLogBlock);
        }
    }

    public function register()
    {

    }
}