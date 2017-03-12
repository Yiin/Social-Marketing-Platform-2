<?php

namespace App\Components;


use Auth;

/**
 * Class DashboardBlock
 * @package App\Components
 */
class DashboardBlock
{
    /**
     * @var string
     */
    private $view;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $requiredPermissions = [];

    /**
     * DashboardBlock constructor.
     * @param $view
     * @param $data
     */
    public function __construct($view, $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * @param $permission
     * @return $this
     */
    public function requiresPermission($permission)
    {
        $this->requiredPermissions [] = $permission;

        return $this;
    }

    /**
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Does user have required permissions to access this dashboard block?
     *
     * @return bool
     */
    public function available()
    {
        foreach ($this->requiredPermissions as $permission) {
            if (!Auth::user()->hasPermissionTo($permission)) {
                return false;
            }
        }
        return true;
    }
}