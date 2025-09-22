<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Bug;
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

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'office@appsaga.io'],
            [
                'name' => 'Appsaga Office',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Create developers
        $dev1 = User::firstOrCreate(
            ['email' => 'deepak@appsaga.io'],
            [
                'name' => 'Deepak Dumraliya',
                'password' => Hash::make('password'),
                'role' => 'developer',
                'email_verified_at' => now(),
            ]
        );
        $dev1->assignRole('developer');

        $dev2 = User::firstOrCreate(
            ['email' => 'avinash@appsaga.io'],
            [
                'name' => 'Avinash Mishtra',
                'password' => Hash::make('password'),
                'role' => 'developer',
                'email_verified_at' => now(),
            ]
        );
        $dev2->assignRole('developer');

        // Create specific testers
        $tester1 = User::firstOrCreate(
            ['email' => 'himanshu@appsaga.io'],
            [
                'name' => 'Himanshu',
                'password' => Hash::make('password'),
                'role' => 'tester',
                'email_verified_at' => now(),
            ]
        );
        $tester1->assignRole('tester');

        $tester2 = User::firstOrCreate(
            ['email' => 'rohit@appsaga.io'],
            [
                'name' => 'Rohit',
                'password' => Hash::make('password'),
                'role' => 'tester',
                'email_verified_at' => now(),
            ]
        );
        $tester2->assignRole('tester');

        // Create specific projects
        $bugtestersProject = Project::firstOrCreate(
            ['name' => 'BugTesters'],
            [
                'description' => 'Main bug tracking and testing platform for Appsaga.io',
                'status' => 'active',
                'created_by' => $admin->id,
            ]
        );

        $testcreaterProject = Project::firstOrCreate(
            ['name' => 'TestCreater'],
            [
                'description' => 'Test case creation and management system',
                'status' => 'active',
                'created_by' => $admin->id,
            ]
        );

        // Create sample bugs for BugTesters project
        Bug::firstOrCreate(
            ['title' => 'Login button not working on mobile'],
            [
                'description' => 'The login button on mobile devices is not responding to touch events. This affects all mobile browsers tested.',
                'severity' => 'high',
                'priority' => 'urgent',
                'status' => 'open',
                'steps_to_reproduce' => '1. Open app on mobile device\n2. Navigate to login page\n3. Tap login button\n4. Observe no response',
                'project_id' => $bugtestersProject->id,
                'reporter_id' => $tester1->id,
                'assigned_to' => $dev1->id,
            ]
        );

        Bug::firstOrCreate(
            ['title' => 'Dashboard loading slowly'],
            [
                'description' => 'The dashboard takes more than 5 seconds to load on first visit. This impacts user experience significantly.',
                'severity' => 'medium',
                'priority' => 'high',
                'status' => 'in_progress',
                'steps_to_reproduce' => '1. Clear browser cache\n2. Visit dashboard\n3. Measure load time\n4. Observe slow loading',
                'project_id' => $bugtestersProject->id,
                'reporter_id' => $tester2->id,
                'assigned_to' => $dev2->id,
            ]
        );

        // Create sample bugs for TestCreater project
        Bug::firstOrCreate(
            ['title' => 'Test case export feature missing'],
            [
                'description' => 'Users cannot export test cases to Excel or PDF format. This is a critical feature for reporting.',
                'severity' => 'critical',
                'priority' => 'urgent',
                'status' => 'open',
                'steps_to_reproduce' => '1. Create test cases\n2. Try to export\n3. Notice no export option available',
                'project_id' => $testcreaterProject->id,
                'reporter_id' => $tester1->id,
                'assigned_to' => $dev1->id,
            ]
        );

        Bug::firstOrCreate(
            ['title' => 'Test case template not saving'],
            [
                'description' => 'Custom test case templates are not being saved properly. Users lose their work when trying to save templates.',
                'severity' => 'high',
                'priority' => 'high',
                'status' => 'testing',
                'steps_to_reproduce' => '1. Create custom template\n2. Fill in template details\n3. Save template\n4. Check if template is saved',
                'project_id' => $testcreaterProject->id,
                'reporter_id' => $tester2->id,
                'assigned_to' => $dev2->id,
            ]
        );

        $this->command->info('Users and projects created successfully!');
        $this->command->info('');
        $this->command->info('Appsaga Team:');
        $this->command->info('Admin: office@appsaga.io / password');
        $this->command->info('Deepak Dumraliya (Dev): deepak@appsaga.io / password');
        $this->command->info('Avinash Mishtra (Dev): avinash@appsaga.io / password');
        $this->command->info('Himanshu (Tester): himanshu@appsaga.io / password');
        $this->command->info('Rohit (Tester): rohit@appsaga.io / password');
        $this->command->info('');
        $this->command->info('Projects:');
        $this->command->info('1. BugTesters - Main bug tracking platform');
        $this->command->info('2. TestCreater - Test case management system');
    }
}
