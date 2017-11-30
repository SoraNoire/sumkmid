<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnusedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('category');
        Schema::dropIfExists('event');
        Schema::dropIfExists('ev_category');
        Schema::dropIfExists('ev_category_relation');
        Schema::dropIfExists('ev_forum_relation');
        Schema::dropIfExists('ev_mentor_relation');
        Schema::dropIfExists('gallery');
        Schema::dropIfExists('gallery_category');
        Schema::dropIfExists('gallery_category_relation');
        Schema::dropIfExists('gallery_tag');
        Schema::dropIfExists('gallery_tag_relation');
        Schema::dropIfExists('page');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_category');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('tag');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('user_has_roles');
        Schema::dropIfExists('user_has_permissions');
        Schema::dropIfExists('video');
        Schema::dropIfExists('video_category');
        Schema::dropIfExists('video_category_relation');
        Schema::dropIfExists('video_tag');
        Schema::dropIfExists('video_tag_relation');
        Schema::rename('nposts','posts');
        Schema::enableForeignKeyConstraints();
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::rename('posts','nposts');
        
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('event', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('ev_category', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('ev_category_relation', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('ev_forum_relation', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('ev_mentor_relation', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('gallery', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('gallery_category', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('gallery_category_relation', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('gallery_tag_relation', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('page', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('post', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('post_category', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('post_tag', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('user_has_permissions', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('video', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('video_category', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('video_category_relation', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('video_tag', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('video_tag_relation', function (Blueprint $table) {
            $table->increments('id');
        });
        
    }
}
