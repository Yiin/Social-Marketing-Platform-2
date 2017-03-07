<?php

namespace App\Services;

use App\Components\NavigationMenuItem;
use App\Constants\Permission;
use App\Models\User;

class NavigationMenuService
{
    private $items = [];

    public function __construct()
    {
        $this->addItem('Dashboard', 'pe-7s-graph', 'dashboard');
        $this->addItem('My Profile', 'pe-7s-user', 'profile');

        $this->addItem('Resellers', 'pe-7s-users', 'reseller.index', Permission::MANAGE_RESELLERS);
        $this->addItem('Clients', 'pe-7s-users', 'client.index', Permission::MANAGE_CLIENTS);

        $this->addItem('Templates', 'pe-7s-note2', 'template.index', Permission::MANAGE_CLIENTS);
    }

    public function addItem($title, $icon, $route, $requires_permision = null)
    {
        $item = new NavigationMenuItem($title, $icon, $route, $requires_permision);

        $this->items [] = $item;

        return $item;
    }

    public function getItems()
    {
        return $this->items;
    }
}

?>