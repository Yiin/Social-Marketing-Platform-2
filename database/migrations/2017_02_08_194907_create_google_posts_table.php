<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGooglePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_posts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('google_queue_id')->unsigned();
            $table->foreign('google_queue_id')->references('id')->on('google_queues')->onUpdate('cascade')->onDelete('cascade');

            $table->string('url');
            $table->text('message');
            $table->string('community_name');

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
        Schema::dropIfExists('google_posts');
    }
}
