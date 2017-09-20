<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        
        // seed user
        $this->call(Modules\UserManager\Database\Seeders\PermissionTableSeeder::class);

        // seed permission
        $this->call(Modules\UserManager\Database\Seeders\UsersTableSeeder::class);
        
    }
}
