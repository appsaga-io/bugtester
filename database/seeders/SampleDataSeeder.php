<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Bug;
use App\Models\User;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users
        $admin = User::where('email', 'admin@example.com')->first();
        $developer = User::where('email', 'developer@example.com')->first();
        $tester = User::where('email', 'tester@example.com')->first();

        if (!$admin || !$developer || !$tester) {
            $this->command->info('Please run the main seeder first to create users.');
            return;
        }

        // Create sample projects
        $project1 = Project::create([
            'name' => 'E-commerce Website',
            'description' => 'A modern e-commerce platform with shopping cart, payment integration, and user management.',
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        $project2 = Project::create([
            'name' => 'Mobile App',
            'description' => 'Cross-platform mobile application for iOS and Android.',
            'status' => 'active',
            'created_by' => $developer->id,
        ]);

        $project3 = Project::create([
            'name' => 'API Service',
            'description' => 'RESTful API service for third-party integrations.',
            'status' => 'completed',
            'created_by' => $admin->id,
        ]);

        // Create sample bugs
        Bug::create([
            'title' => 'Login button not working on mobile',
            'description' => 'Users are unable to login when using mobile devices. The login button appears to be unresponsive.',
            'steps_to_reproduce' => "1. Open the website on mobile\n2. Click the login button\n3. Nothing happens",
            'severity' => 'high',
            'status' => 'open',
            'priority' => 'high',
            'project_id' => $project1->id,
            'reporter_id' => $tester->id,
            'assigned_to' => $developer->id,
        ]);

        Bug::create([
            'title' => 'Payment gateway timeout error',
            'description' => 'Payment processing fails with timeout error after 30 seconds.',
            'steps_to_reproduce' => "1. Add items to cart\n2. Proceed to checkout\n3. Enter payment details\n4. Click pay\n5. Wait 30 seconds\n6. Timeout error appears",
            'severity' => 'critical',
            'status' => 'in_progress',
            'priority' => 'urgent',
            'project_id' => $project1->id,
            'reporter_id' => $tester->id,
            'assigned_to' => $developer->id,
        ]);

        Bug::create([
            'title' => 'App crashes on Android 12',
            'description' => 'The mobile app crashes immediately after launch on Android 12 devices.',
            'steps_to_reproduce' => "1. Install app on Android 12 device\n2. Launch the app\n3. App crashes immediately",
            'severity' => 'critical',
            'status' => 'open',
            'priority' => 'urgent',
            'project_id' => $project2->id,
            'reporter_id' => $tester->id,
        ]);

        Bug::create([
            'title' => 'API returns 500 error for large requests',
            'description' => 'API endpoints return 500 internal server error when processing large payloads.',
            'steps_to_reproduce' => "1. Send POST request with large JSON payload (>1MB)\n2. API returns 500 error",
            'severity' => 'medium',
            'status' => 'resolved',
            'priority' => 'medium',
            'project_id' => $project3->id,
            'reporter_id' => $developer->id,
            'assigned_to' => $developer->id,
            'resolved_at' => now(),
        ]);

        Bug::create([
            'title' => 'Search results not displaying correctly',
            'description' => 'Search results are not displaying in the correct order and some results are missing.',
            'steps_to_reproduce' => "1. Go to search page\n2. Enter search term\n3. Results appear in wrong order",
            'severity' => 'low',
            'status' => 'open',
            'priority' => 'low',
            'project_id' => $project1->id,
            'reporter_id' => $tester->id,
        ]);

        $this->command->info('Sample data created successfully!');
    }
}
