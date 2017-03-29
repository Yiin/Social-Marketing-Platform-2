<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkedinTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linkedin_accounts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('email')->unique();
            $table->string('password');

            $table->timestamps();
        });

        Schema::create('linkedin_groups', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('groupId');
            $table->integer('members');
            $table->integer('linkedin_account_id')->unsigned();
            $table->foreign('linkedin_account_id')->references('id')->on('linkedin_accounts')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('linkedin_queues', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('template_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('post_count')->default(0);
            $table->integer('backlinks')->default(0);
            $table->integer('jobs')->default(0);

            $table->timestamps();
        });

        Schema::create('linkedin_posts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('linkedin_queue_id')->unsigned();
            $table->foreign('linkedin_queue_id')->references('id')->on('linkedin_queues')->onUpdate('cascade')->onDelete('cascade');

            $table->string('url');
            $table->text('message');
            $table->string('group_name');

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
        Schema::dropIfExists('linkedin_posts');
        Schema::dropIfExists('linkedin_queues');
        Schema::dropIfExists('linkedin_groups');
        Schema::dropIfExists('linkedin_accounts');
    }
}
