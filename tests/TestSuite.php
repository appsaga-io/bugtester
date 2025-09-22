<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Comprehensive Test Suite for BugTester Application
 *
 * This class provides a centralized way to run all tests
 * and provides test utilities for the entire application.
 */
class TestSuite extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test data factories
     */
    protected function createAdminUser()
    {
        return \App\Models\User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com'
        ]);
    }

    protected function createRegularUser()
    {
        return \App\Models\User::factory()->create([
            'role' => 'user',
            'email' => 'user@example.com'
        ]);
    }

    protected function createProject($user = null)
    {
        if (!$user) {
            $user = $this->createAdminUser();
        }

        return \App\Models\Project::factory()->create([
            'created_by' => $user->id
        ]);
    }

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

    /**
     * Assert that a user has specific permissions
     */
    protected function assertUserCan($user, $permission)
    {
        $this->assertTrue($user->can($permission), "User should be able to {$permission}");
    }

    protected function assertUserCannot($user, $permission)
    {
        $this->assertFalse($user->can($permission), "User should not be able to {$permission}");
    }

    /**
     * Assert that a bug has specific status
     */
    protected function assertBugStatus($bug, $expectedStatus)
    {
        $this->assertEquals($expectedStatus, $bug->fresh()->status, "Bug should have status {$expectedStatus}");
    }

    /**
     * Assert that a project has specific status
     */
    protected function assertProjectStatus($project, $expectedStatus)
    {
        $this->assertEquals($expectedStatus, $project->fresh()->status, "Project should have status {$expectedStatus}");
    }

    /**
     * Create a complete bug workflow for testing
     */
    protected function createBugWorkflow()
    {
        $admin = $this->createAdminUser();
        $developer = $this->createRegularUser();
        $project = $this->createProject($admin);

        $bug = $this->createBug($project, $admin);
        $bug->update(['assigned_to' => $developer->id]);

        return [
            'admin' => $admin,
            'developer' => $developer,
            'project' => $project,
            'bug' => $bug
        ];
    }

    /**
     * Assert that a Livewire component has specific data
     */
    protected function assertLivewireData($component, $key, $expectedValue)
    {
        $this->assertEquals($expectedValue, $component->get($key), "Livewire component should have {$key} = {$expectedValue}");
    }

    /**
     * Assert that a Livewire component emits a specific event
     */
    protected function assertLivewireEmitted($component, $event)
    {
        $this->assertTrue($component->hasEmitted($event), "Livewire component should have emitted {$event}");
    }

    /**
     * Create test data for performance testing
     */
    protected function createPerformanceTestData()
    {
        $admin = $this->createAdminUser();
        $project = $this->createProject($admin);

        // Create multiple bugs with different statuses
        for ($i = 0; $i < 50; $i++) {
            $this->createBug($project, $admin);
        }

        return [
            'admin' => $admin,
            'project' => $project
        ];
    }

    /**
     * Assert that a response has proper JSON structure
     */
    protected function assertJsonStructure($response, $structure)
    {
        $response->assertJsonStructure($structure);
    }

    /**
     * Assert that a database has specific records
     */
    protected function assertDatabaseHasRecord($table, $data)
    {
        $this->assertDatabaseHas($table, $data);
    }

    /**
     * Assert that a database does not have specific records
     */
    protected function assertDatabaseMissingRecord($table, $data)
    {
        $this->assertDatabaseMissing($table, $data);
    }
}
