<?php

namespace Modules\UserManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = User::create(
    				[
    					'name' => 'Admin',
    					'username' => 'admin',
    					'email' => 'admin@admin.com',
    					'password' => bcrypt('admin123'),		
    				]
				);

        $user->assignRole('admin');
    }
}
