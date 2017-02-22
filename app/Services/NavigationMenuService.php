<?php

namespace App\Services;

class NavigationMenuItem
{
    private $childs;

    public $route;
    public $title;
    public $icon;

    function __construct($title, $icon, $route)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->route = $route;
        $this->childs = [];
    }

    public function addChild($title, $icon, $route)
    {
        $item = new self($title, $icon, $route);

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

class NavigationMenuService
{
    private $items = [];

    public function addItem($title, $icon, $route)
    {
        $item = new NavigationMenuItem($title, $icon, $route);

        $this->items [] = $item;

        return $item;
    }

    public function getItems()
    {
        return $this->items;
    }
}

?>