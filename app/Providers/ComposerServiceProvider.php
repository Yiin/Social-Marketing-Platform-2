<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    protected $pages;

    public function __construct($app)
    {
        $this->pages = collect(config('app.routes'));

        parent::__construct($app);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \View::composer('*', function ($view) {
            $view->with('user', \Auth::user())
                ->with('currentPageTitle', $this->currentPageTitle())
                ->with('currentPageIcon', $this->currentPageIcon())
                ->with('navLinks', $this->navLinks());
        });
    }

    private function currentPageTitle()
    {
        return $this->pages->where('route', \Route::currentRouteName())->first()['title'];
    }

    private function currentPageIcon()
    {
        return $this->pages->where('route', \Route::currentRouteName())->first()['icon'];
    }

    private function navLinks()
    {
        $navLinks = [];

        foreach ($this->pages as $linkData) {
            $link = new \stdClass;

            $link->route = $linkData['route'];
            $link->icon = $linkData['icon'];
            $link->title = $linkData['title'];

            $navLinks[] = $link;
        }

        return $navLinks;
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
