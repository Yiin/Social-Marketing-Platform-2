<?php

namespace App\Components;

class NavigationMenuItem
{
    private $childs;

    public $route;
    public $title;
    public $icon;
    public $requires_permision;

    function __construct($title, $icon, $route, $guard = null)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->route = $route;
        $this->requires_permision = $guard;
        $this->childs = [];
    }


//    public function canShow

    public function addChild($title, $icon, $route, $guard = null)
    {
        $item = new self($title, $icon, $route, $guard);

        $this->childs [] = $item;

        return $this;
    }

    public function getChilds()
    {
        return $this->childs;
    }

    public function href()
    {
        return route($this->route);
    }
}