<?php

namespace App\Constants;

use Spatie\Permission\Exceptions\RoleDoesNotExist;

/**
 * Class Role
 * @package App\Constants
 */
class Role
{
    const ADMIN = 'admin';
    const RESELLER = 'reseller';
    const CLIENT = 'client';

    /**
     * @param $name
     * @return null|\Spatie\Permission\Models\Role
     */
    public function find($name)
    {
        try {
            return \Spatie\Permission\Models\Role::findByName($name);
        } catch (RoleDoesNotExist $e) {
            return null;
        }
    }
}