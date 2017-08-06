<?php

namespace App\Constants;

use Spatie\Permission\Exceptions\PermissionDoesNotExist;


/**
 * Class Permission
 * @package App\Constants
 */
class Permission
{
    const MANAGE_RESELLERS = 'manage_resellers';
    const MANAGE_CLIENTS = 'manage_clients';
    const USE_ALL_SERVICES = 'use_all_services';
    const VIEW_ERRORS_LOG = 'view_errors_log';
    const VIEW_FACEBOOK_STATS_ALL = 'view_facebook_stats_all';

    /**
     * Returns permission
     *
     * @param $name
     * @return \Spatie\Permission\Models\Permission|null
     */
    static public function find($name)
    {
        try {
            return \Spatie\Permission\Models\Permission::findByName($name);
        } catch (PermissionDoesNotExist $e) {
            return null;
        }
    }
}