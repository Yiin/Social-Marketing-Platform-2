<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddErrorLogPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $viewErrorsLog = Permission::create(['name' => \App\Constants\Permission::VIEW_ERRORS_LOG]);

        Role::findByName(\App\Constants\Role::ADMIN)->givePermissionTo($viewErrorsLog);
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
