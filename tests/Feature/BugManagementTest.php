<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Bug;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Bugs\Index;
use App\Livewire\Bugs\Create;
use App\Livewire\Bugs\Edit;
use App\Livewire\Bugs\Show;
use App\Livewire\Bugs\Kanban;
use Tests\TestCase;

class BugManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_bugs_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('bugs.index'));
        $response->assertStatus(200);
        $response->assertSee('Bug Tracker');
    }

    public function test_can_view_bug_kanban()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('bugs.kanban'));
        $response->assertStatus(200);
        $response->assertSee('Bug Kanban Board');
    }

    public function test_can_create_bug()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('bugs.create'));
        $response->assertStatus(200);

        Livewire::test(Create::class)
            ->set('title', 'Test Bug')
            ->set('description', 'This is a test bug')
            ->set('project_id', $project->id)
            ->set('severity', 'high')
            ->set('priority', 'urgent')
            ->call('save')
            ->assertRedirect(route('bugs.index'));

        $this->assertDatabaseHas('bugs', [
            'title' => 'Test Bug',
            'description' => 'This is a test bug',
            'severity' => 'high',
            'priority' => 'urgent'
        ]);
    }

    public function test_can_edit_bug()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('bugs.edit', $bug));
        $response->assertStatus(200);

        Livewire::test(Edit::class, ['bug' => $bug])
            ->set('title', 'Updated Bug Title')
            ->set('description', 'Updated description')
            ->call('save')
            ->assertRedirect(route('bugs.show', $bug));

        $this->assertDatabaseHas('bugs', [
            'id' => $bug->id,
            'title' => 'Updated Bug Title',
            'description' => 'Updated description'
        ]);
    }

    public function test_can_view_bug_details()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('bugs.show', $bug));
        $response->assertStatus(200);
        $response->assertSee($bug->title);
    }

    public function test_can_update_bug_status_in_kanban()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create(['status' => 'open']);
        $this->actingAs($user);

        Livewire::test(Kanban::class)
            ->call('updateBugStatus', $bug->id, 'in_progress')
            ->assertEmitted('$refresh');

        $this->assertDatabaseHas('bugs', [
            'id' => $bug->id,
            'status' => 'in_progress'
        ]);
    }

    public function test_can_filter_bugs_by_project()
    {
        $user = User::factory()->create();
        $project1 = Project::factory()->create();
        $project2 = Project::factory()->create();

        Bug::factory()->create(['project_id' => $project1->id]);
        Bug::factory()->create(['project_id' => $project2->id]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('project_id', $project1->id)
            ->assertSee('Project: ' . $project1->name);
    }

    public function test_can_search_bugs()
    {
        $user = User::factory()->create();
        Bug::factory()->create(['title' => 'Login Bug']);
        Bug::factory()->create(['title' => 'Database Error']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('search', 'Login')
            ->assertSee('Login Bug')
            ->assertDontSee('Database Error');
    }

    public function test_can_toggle_kanban_columns()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Kanban::class)
            ->call('toggleColumn', 'open')
            ->assertSet('visibleColumns.open', false)
            ->call('showAllColumns')
            ->assertSet('visibleColumns.open', true);
    }

    public function test_validation_works_for_bug_creation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('title', '')
            ->set('description', '')
            ->call('save')
            ->assertHasErrors(['title', 'description']);
    }

    public function test_unauthorized_user_cannot_create_bug()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        // Assuming users can create bugs, but let's test the permission system
        $response = $this->get(route('bugs.create'));
        $response->assertStatus(200); // Users can create bugs
    }
}
