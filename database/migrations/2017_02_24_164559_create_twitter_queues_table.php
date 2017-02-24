<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwitterQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_queues', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('template_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('tweet_count')->default(0);
            $table->integer('jobs')->default(0);

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
        Schema::dropIfExists('twitter_queues');
    }
}
