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
        Permission::create(['name' => 'create vaccination']);
        Permission::create(['name' => 'read vaccination']);
        Permission::create(['name' => 'update vaccination']);
        Permission::create(['name' => 'delete vaccination']);

        Permission::create(['name' => 'create pet']);
        Permission::create(['name' => 'read pet']);
        Permission::create(['name' => 'update pet']);
        Permission::create(['name' => 'delete pet']);

        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'read user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'create address']);
        Permission::create(['name' => 'read address']);
        Permission::create(['name' => 'update address']);
        Permission::create(['name' => 'delete address']);

        Permission::create(['name' => 'read vaccine']);
        Permission::create(['name' => 'create vaccine']);

        // Create roles and assign existing permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['create vaccination', 'read vaccination', 'update vaccination', 'delete vaccination', 'create pet', 'read pet', 'update pet', 'delete pet', 'create user', 'read user', 'update user', 'delete user', 'create vaccine', 'read vaccine', 'create address', 'read address', 'update address', 'delete address',]);

        $vetRole = Role::create(['name' => 'vet']);
        $vetRole->givePermissionTo(['create vaccination', 'read vaccination', 'update vaccination', 'delete vaccination', 'create pet', 'read pet', 'update pet', 'delete pet', 'create user', 'read user', 'update user', 'delete user', 'read vaccine', 'create address', 'read address', 'update address', 'delete address']);

        $ownerRole = Role::create(['name' => 'owner']);
        $ownerRole->givePermissionTo(['create pet', 'read pet', 'update pet', 'delete pet', 'read user', 'update user', 'create address', 'read address', 'update address', 'read vaccination']);
    }
}
