<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Blog\Entities\Option;

class InsertOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $name[] = (['name' => 'analytic', 'value' => '']);
        $name[] = (['name' => 'fb_pixel', 'value' => '']);
        $name[] = (['name' => 'link_fb', 'value' => '']);
        $name[] = (['name' => 'link_tw', 'value' => '']);
        $name[] = (['name' => 'link_ig', 'value' => '']);
        $name[] = (['name' => 'link_in', 'value' => '']);
        $name[] = (['name' => 'link_yt', 'value' => '']);

        for ($i=0; $i < count($name) ; $i++) { 
            $save = Option::where('key', $name[$i]['name'])->first();
            if (!isset($save)) {
                $save = new Option;
                $save -> key = $name[$i]['name'];
                $save -> value = $name[$i]['value'];
            }
            $save->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
