<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BugTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->developer = User::factory()->create(['role' => 'developer']);
        $this->tester = User::factory()->create(['role' => 'tester']);

        // Create test project
        $this->project = Project::factory()->create(['created_by' => $this->admin->id]);
    }

    public function test_can_view_bugs_index(): void
    {
        $response = $this->actingAs($this->tester)->get('/bugs');

        $response->assertStatus(200);
        $response->assertSee('Bugs');
    }

    public function test_can_create_bug(): void
    {
        $bugData = [
            'title' => 'Test Bug',
            'description' => 'This is a test bug',
            'project_id' => $this->project->id,
            'severity' => 'medium',
            'priority' => 'medium',
        ];

        $response = $this->actingAs($this->tester)->post('/bugs', $bugData);

        $this->assertDatabaseHas('bugs', [
            'title' => 'Test Bug',
            'description' => 'This is a test bug',
            'project_id' => $this->project->id,
        ]);
    }

    public function test_can_view_kanban_board(): void
    {
        $response = $this->actingAs($this->tester)->get('/bugs/kanban');

        $response->assertStatus(200);
        $response->assertSee('Bug Kanban Board');
    }

    public function test_api_can_list_bugs(): void
    {
        Bug::factory()->create([
            'project_id' => $this->project->id,
            'reporter_id' => $this->tester->id,
        ]);

        $response = $this->getJson('/api/bugs');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'severity',
                            'status',
                        ]
                    ]
                ]);
    }

    public function test_api_can_create_bug(): void
    {
        $bugData = [
            'title' => 'API Test Bug',
            'description' => 'Bug created via API',
            'project_id' => $this->project->id,
            'severity' => 'high',
            'priority' => 'urgent',
        ];

        $response = $this->postJson('/api/bugs', $bugData);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Bug created successfully',
                ]);

        $this->assertDatabaseHas('bugs', [
            'title' => 'API Test Bug',
            'project_id' => $this->project->id,
        ]);
    }

    public function test_dashboard_shows_statistics(): void
    {
        Bug::factory()->count(3)->create([
            'project_id' => $this->project->id,
            'reporter_id' => $this->tester->id,
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Total Bugs');
        $response->assertSee('3'); // Should show the count
    }
}
