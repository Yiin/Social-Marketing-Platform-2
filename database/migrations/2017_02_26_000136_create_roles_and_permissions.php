<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRolesAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create permissions
        $canManageResellers = Permission::create(['name' => User::MANAGE_RESELLERS]);
        $canManageClients = Permission::create(['name' => User::MANAGE_CLIENTS]);
        $canUseAllServices = Permission::create(['name' => User::USE_ALL_SERVICES]);

        // Create roles
        $admin = Role::create(['name' => User::ROLE_ADMIN]);
        $reseller = Role::create(['name' => User::ROLE_RESELLER]);
        Role::create(['name' => User::ROLE_CLIENT]);

        // Assign permissions to roles
        $admin->givePermissionTo($canManageResellers, $canManageClients, $canUseAllServices);
        $reseller->givePermissionTo($canManageClients, $canUseAllServices);

        // Delete one if exists and create root admin user
        User::where('name', 'admin')->delete();
        $user = new User([
            'name' => env('ADMIN_USERNAME', 'root'),
            'email' => env('ADMIN_EMAIL', 'admin'),
            'api_token' => str_random(60)
        ]);
        $user->password = bcrypt(env('ADMIN_PASSWORD'));
        $user->save();

        $user->assignRole($admin);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
