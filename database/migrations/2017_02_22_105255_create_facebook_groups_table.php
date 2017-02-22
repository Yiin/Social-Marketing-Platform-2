<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_groups', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('facebook_account_id')->unsigned();
            $table->foreign('facebook_account_id')->references('id')->on('facebook_accounts')->onUpdate('cascade')->onDelete('cascade');

            $table->string('name');
            $table->string('groupId');
            $table->integer('members');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facebook_groups');
    }
}
