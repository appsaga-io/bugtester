<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolePermissionSeeder::class);

        // Initialize system settings
        $this->call(LogoSeeder::class);

        // Create admin user if it doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Create developer user if it doesn't exist
        $developer = User::firstOrCreate(
            ['email' => 'developer@example.com'],
            [
                'name' => 'Developer User',
                'password' => Hash::make('password'),
                'role' => 'developer',
                'email_verified_at' => now(),
            ]
        );
        $developer->assignRole('developer');

        // Create tester user if it doesn't exist
        $tester = User::firstOrCreate(
            ['email' => 'tester@example.com'],
            [
                'name' => 'Tester User',
                'password' => Hash::make('password'),
                'role' => 'tester',
                'email_verified_at' => now(),
            ]
        );
        $tester->assignRole('tester');

        $this->command->info('Users created successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Developer: developer@example.com / password');
        $this->command->info('Tester: tester@example.com / password');
    }
}
