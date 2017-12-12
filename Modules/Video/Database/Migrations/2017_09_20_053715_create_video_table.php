<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('video', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('title')->nullable();
        //     $table->string('slug')->unique()->nullable();
        //     $table->text('body')->nullable();
        //     $table->string('video_url')->nullable();
        //     $table->string('featured_img')->nullable();
        //     $table->integer('author');
        //     $table->integer('status')->default(0);
        //     $table->text('option')->nullable();
        //     $table->datetime('published_at')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video');
    }
}
