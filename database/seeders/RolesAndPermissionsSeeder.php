<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);

        // Create roles and assign existing permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('manage users');
        $role->givePermissionTo('edit articles');
        $role->givePermissionTo('delete articles');

        $role = Role::create(['name' => 'vet']);
        $role->givePermissionTo('edit articles');

        $role = Role::create(['name' => 'owner']);
        // No permissions given to viewer
    }
}
