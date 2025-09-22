<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Project permissions
            'view-projects',
            'create-projects',
            'edit-projects',
            'delete-projects',

            // Bug permissions
            'view-bugs',
            'create-bugs',
            'edit-bugs',
            'delete-bugs',
            'assign-bugs',
            'resolve-bugs',

            // User permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Dashboard permissions
            'view-dashboard',
            'view-analytics',

            // API permissions
            'api-create-bugs',
            'api-view-bugs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $developerRole = Role::firstOrCreate(['name' => 'developer']);
        $testerRole = Role::firstOrCreate(['name' => 'tester']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());

        $developerRole->givePermissionTo([
            'view-projects',
            'view-bugs',
            'create-bugs',
            'edit-bugs',
            'assign-bugs',
            'resolve-bugs',
            'view-dashboard',
            'api-create-bugs',
            'api-view-bugs',
        ]);

        $testerRole->givePermissionTo([
            'view-projects',
            'view-bugs',
            'create-bugs',
            'view-dashboard',
            'api-create-bugs',
            'api-view-bugs',
        ]);
    }
}
