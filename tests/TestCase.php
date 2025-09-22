<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up test environment - only run migrations, not fresh
        $this->artisan('migrate');
    }

    /**
     * Create an admin user for testing
     */
    protected function createAdminUser()
    {
        return \App\Models\User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com'
        ]);
    }

    /**
     * Create a regular user for testing
     */
    protected function createRegularUser()
    {
        return \App\Models\User::factory()->create([
            'role' => 'user',
            'email' => 'user@example.com'
        ]);
    }

    /**
     * Create a project for testing
     */
    protected function createProject($user = null)
    {
        if (!$user) {
            $user = $this->createAdminUser();
        }

        return \App\Models\Project::factory()->create([
            'created_by' => $user->id
        ]);
    }

    /**
     * Create a bug for testing
     */
    protected function createBug($project = null, $reporter = null)
    {
        if (!$project) {
            $project = $this->createProject();
        }

        if (!$reporter) {
            $reporter = $this->createAdminUser();
        }

        return \App\Models\Bug::factory()->create([
            'project_id' => $project->id,
            'reporter_id' => $reporter->id
        ]);
    }
}
