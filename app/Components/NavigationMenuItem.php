<?php

namespace App\Components;

class NavigationMenuItem
{
    private $childs;

    public $route;
    public $title;
    public $icon;
    public $requires_permision;

    function __construct($title, $icon, $route, $requires_permision = null)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->route = $route;
        $this->requires_permision = $requires_permision;
        $this->childs = [];
    }

    public function addChild($title, $icon, $route, $requires_permision = null)
    {
        $item = new self($title, $icon, $route, $requires_permision);

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