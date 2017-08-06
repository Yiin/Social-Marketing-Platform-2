<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_groups', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('google_account_id')->unsigned();
            $table->foreign('google_account_id')->references('id')->on('google_accounts')->onUpdate('cascade')->onDelete('cascade');

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
        //
    }
}
