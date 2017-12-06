<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class postVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            'slug' => str_random(10),
            'title' => str_random(10),
            'post_type' => 'video',
            'featured_image' => 'https://s3-ap-southeast-1.amazonaws.com/mdirect/shbtm/media/1512480778.jpg',
            'content' => '<p>Dicta inventore dui tristique fugiat est necessitatibus dictum saepe nulla perspiciatis fermentum molestiae tempore, veritatis, exercitation. Vivamus eros quibusdam error. Accumsan impedit morbi sociis beatae.</p>',
            'author' => 2, 
            'status' => 1, 
        ]);
    }
}
