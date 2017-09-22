<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('featured_img')->nullable();
            $table->string('event_type')->nullable();
            $table->integer('mentor_id')->nullable();
            $table->string('location')->nullable();
            $table->string('htm')->nullable();
            $table->text('option')->nullable();
            $table->integer('author')->nullable();
            $table->integer('status')->default(0);
            $table->datetime('open_at');
            $table->datetime('closed_at');
            $table->datetime('published_at');
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
        Schema::dropIfExists('event');
    }
}
