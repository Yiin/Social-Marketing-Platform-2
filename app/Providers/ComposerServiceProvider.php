<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \View::composer('*', function ($view) {
            $view
                ->with('user', \Auth::user())
                ->with('navigationMenu', resolve('App\Services\NavigationMenuService'))
                ->with('currentPageTitle', $this->currentPageTitle())
                ->with('currentPageIcon', $this->currentPageIcon());
        });
    }

    private function currentPageTitle()
    {
        return \Route::currentRouteName();
    }

    private function currentPageIcon()
    {
        return '';
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
