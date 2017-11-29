<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostMetaRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_meta', function($table){
            $table->biginteger('post_id')->unsigned()->change();
            $table->foreign('post_id')->references('id')->on('nposts')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_meta', function($table){
            $table->dropForeign(['post_id']);
        });
    }
}
