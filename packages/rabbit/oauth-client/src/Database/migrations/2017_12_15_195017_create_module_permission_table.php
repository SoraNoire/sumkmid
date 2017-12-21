<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('module_permissions', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('module_id')->unsigned();
          $table->string('role');
          $table->integer('read')->default(0);
          $table->integer('write')->default(0);
          $table->integer('edit')->default(0);
          $table->integer('delete')->default(0);

          $table->foreign('module_id')->references('id')->on('modules')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_permissions');
    }
}
