<?php

namespace App\Pages;

use App\Services\DashboardService;
use Illuminate\Support\ServiceProvider;
use View;

class DashboardPage extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('dashboard', function (\Illuminate\View\View $view) {
            $view->with('blocks', $this->app->make(DashboardService::class)->getBlocks());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
