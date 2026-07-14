<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Permissions
        $permissions = [
            'manage_events' => 'Can create, update, delete events',
            'manage_users' => 'Can manage users and roles',
            'manage_content' => 'Can manage stages, acts, and artists',
            'attend_events' => 'Can mark attendance for events',
            'manage_friends' => 'Can add or remove friends',
        ];

        $permissionModels = [];
        foreach ($permissions as $name => $desc) {
            $permissionModels[$name] = Permission::updateOrCreate(
                ['name' => $name],
                ['description' => $desc]
            );
        }

        // 2. Create Roles
        $adminRole = Role::updateOrCreate(['name' => 'admin'], ['description' => 'Full access to the system']);
        $modRole = Role::updateOrCreate(['name' => 'moderator'], ['description' => 'Can manage events and content']);
        $userRole = Role::updateOrCreate(['name' => 'user'], ['description' => 'Normal user access']);

        // 3. Assign Permissions to Roles
        $adminRole->permissions()->sync(array_values(array_map(fn($m) => $m->id, $permissionModels)));

        $modRole->permissions()->sync([
            $permissionModels['manage_events']->id,
            $permissionModels['manage_content']->id,
        ]);

        $userRole->permissions()->sync([
            $permissionModels['attend_events']->id,
            $permissionModels['manage_friends']->id,
        ]);

        // 4. Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@bangers.nl'],
            [
                'username' => 'admin',
                'password' => Hash::make('bangers2026'), // Temporary hashed password
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'is_verified' => true,
            ]
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
