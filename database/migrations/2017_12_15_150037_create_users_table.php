<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
          $table->bigincrements('id');
          $table->biginteger('master_id')->unsigned();
          $table->string('username');
          $table->string('name');
          $table->string('avatar');
          $table->text('token');
          $table->text('options');
          $table->string('role');
          $table->string('sessid');
          $table->string('cookieid');
          $table->text('description');
          $table->timestamp('last_sync');
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
        Schema::dropIfExists('users');
    }
}
