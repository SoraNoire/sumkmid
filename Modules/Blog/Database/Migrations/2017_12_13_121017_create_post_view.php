<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreatePostView extends Migration
{
    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
         $query = '(SELECT 
                *
            FROM 
                posts 
            WHERE 
                status = 1
            AND
                deleted = 0
            AND
                published_date <= NOW()
            ORDER BY    
                published_date DESC
            )';

        DB::statement('CREATE VIEW post_view AS '.$query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW post_view');
    }
}
