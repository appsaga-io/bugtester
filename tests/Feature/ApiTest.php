<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Bug;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_bugs_api()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/bugs');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'severity',
                    'priority',
                    'status',
                    'project',
                    'reporter',
                    'assignee'
                ]
            ]
        ]);
    }

    public function test_can_create_bug_api()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $this->actingAs($user, 'sanctum');

        $bugData = [
            'title' => 'API Test Bug',
            'description' => 'This is a test bug created via API',
            'project_id' => $project->id,
            'severity' => 'high',
            'priority' => 'urgent'
        ];

        $response = $this->postJson('/api/bugs', $bugData);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'title' => 'API Test Bug',
            'description' => 'This is a test bug created via API'
        ]);

        $this->assertDatabaseHas('bugs', [
            'title' => 'API Test Bug',
            'project_id' => $project->id
        ]);
    }

    public function test_can_update_bug_api()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create();

        $this->actingAs($user, 'sanctum');

        $updateData = [
            'title' => 'Updated Bug Title',
            'status' => 'in_progress'
        ];

        $response = $this->putJson("/api/bugs/{$bug->id}", $updateData);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Updated Bug Title',
            'status' => 'in_progress'
        ]);

        $this->assertDatabaseHas('bugs', [
            'id' => $bug->id,
            'title' => 'Updated Bug Title',
            'status' => 'in_progress'
        ]);
    }

    public function test_can_delete_bug_api()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->deleteJson("/api/bugs/{$bug->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('bugs', [
            'id' => $bug->id
        ]);
    }

    public function test_can_get_projects_api()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/projects');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'status',
                    'creator',
                    'bugs_count'
                ]
            ]
        ]);
    }

    public function test_can_create_project_api()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $projectData = [
            'name' => 'API Test Project',
            'description' => 'This is a test project created via API',
            'status' => 'active'
        ];

        $response = $this->postJson('/api/projects', $projectData);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'API Test Project',
            'description' => 'This is a test project created via API'
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'API Test Project',
            'created_by' => $user->id
        ]);
    }

    public function test_can_get_users_api()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin, 'sanctum');

        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'created_at'
                ]
            ]
        ]);
    }

    public function test_api_requires_authentication()
    {
        $response = $this->getJson('/api/bugs');
        $response->assertStatus(401);
    }

    public function test_api_validation_works()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/bugs', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description', 'project_id']);
    }

    public function test_can_get_bug_statistics_api()
    {
        $user = User::factory()->create();

        Bug::factory()->create(['status' => 'open']);
        Bug::factory()->create(['status' => 'in_progress']);
        Bug::factory()->create(['status' => 'resolved']);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/statistics');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'bugs' => [
                'total',
                'open',
                'in_progress',
                'resolved',
                'closed'
            ],
            'projects' => [
                'total',
                'active',
                'completed',
                'on_hold'
            ]
        ]);
    }
}
