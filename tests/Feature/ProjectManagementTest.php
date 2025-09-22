<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Bug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Projects\Index;
use App\Livewire\Projects\Create;
use App\Livewire\Projects\Edit;
use App\Livewire\Projects\Show;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_projects_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('projects.index'));
        $response->assertStatus(200);
        $response->assertSee('Projects');
    }

    public function test_can_create_project()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('projects.create'));
        $response->assertStatus(200);

        Livewire::test(Create::class)
            ->set('name', 'Test Project')
            ->set('description', 'This is a test project')
            ->set('status', 'active')
            ->call('save')
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'description' => 'This is a test project',
            'status' => 'active',
            'created_by' => $user->id
        ]);
    }

    public function test_can_edit_project()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['created_by' => $user->id]);
        $this->actingAs($user);

        $response = $this->get(route('projects.edit', $project));
        $response->assertStatus(200);

        Livewire::test(Edit::class, ['project' => $project])
            ->set('name', 'Updated Project Name')
            ->set('description', 'Updated description')
            ->call('save')
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Project Name',
            'description' => 'Updated description'
        ]);
    }

    public function test_can_view_project_details()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('projects.show', $project));
        $response->assertStatus(200);
        $response->assertSee($project->name);
    }

    public function test_can_filter_projects_by_status()
    {
        $user = User::factory()->create();
        Project::factory()->create(['status' => 'active']);
        Project::factory()->create(['status' => 'completed']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('status', 'active')
            ->assertSee('Status: Active');
    }

    public function test_can_search_projects()
    {
        $user = User::factory()->create();
        Project::factory()->create(['name' => 'Frontend Project']);
        Project::factory()->create(['name' => 'Backend Project']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('search', 'Frontend')
            ->assertSee('Frontend Project')
            ->assertDontSee('Backend Project');
    }

    public function test_project_shows_bug_statistics()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Bug::factory()->create(['project_id' => $project->id, 'status' => 'open']);
        Bug::factory()->create(['project_id' => $project->id, 'status' => 'resolved']);

        $this->actingAs($user);

        $response = $this->get(route('projects.show', $project));
        $response->assertSee('Total Bugs: 2');
        $response->assertSee('Open: 1');
        $response->assertSee('Resolved: 1');
    }

    public function test_validation_works_for_project_creation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name']);
    }

    public function test_can_complete_project()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        Livewire::test(Edit::class, ['project' => $project])
            ->set('status', 'completed')
            ->call('save');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'status' => 'completed'
        ]);
    }

    public function test_can_put_project_on_hold()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        Livewire::test(Edit::class, ['project' => $project])
            ->set('status', 'on_hold')
            ->call('save');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'status' => 'on_hold'
        ]);
    }
}
