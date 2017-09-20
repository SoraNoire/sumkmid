<?php

namespace Modules\UserManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // clear cache first
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        Permission::create(['name' => 'create articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'administration']);


        // create roles and assign existing permissions
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo('create articles');
        $role->givePermissionTo('edit articles');

        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo('edit articles');

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('administration');
        $role->givePermissionTo('edit articles');
        $role->givePermissionTo('delete articles');


    }
}
