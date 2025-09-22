<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Bug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Bugs\Kanban;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_bug_lifecycle()
    {
        // Create users and project
        $admin = User::factory()->create(['role' => 'admin']);
        $developer = User::factory()->create(['role' => 'user']);
        $project = Project::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        // 1. Create a bug
        $bug = Bug::factory()->create([
            'project_id' => $project->id,
            'reporter_id' => $admin->id,
            'status' => 'open',
            'severity' => 'high',
            'priority' => 'urgent'
        ]);

        $this->assertDatabaseHas('bugs', [
            'id' => $bug->id,
            'status' => 'open'
        ]);

        // 2. Assign bug to developer
        $bug->update(['assigned_to' => $developer->id]);

        $this->assertDatabaseHas('bugs', [
            'id' => $bug->id,
            'assigned_to' => $developer->id
        ]);

        // 3. Move bug to in_progress via Kanban
        Livewire::test(Kanban::class)
            ->call('updateBugStatus', $bug->id, 'in_progress');

        $this->assertDatabaseHas('bugs', [
            'id' => $bug->id,
            'status' => 'in_progress'
        ]);

        // 4. Move to testing
        Livewire::test(Kanban::class)
            ->call('updateBugStatus', $bug->id, 'testing');

        $this->assertDatabaseHas('bugs', [
            'id' => $bug->id,
            'status' => 'testing'
        ]);

        // 5. Resolve the bug
        Livewire::test(Kanban::class)
            ->call('updateBugStatus', $bug->id, 'resolved');

        $this->assertDatabaseHas('bugs', [
            'id' => $bug->id,
            'status' => 'resolved'
        ]);

        // 6. Close the bug
        Livewire::test(Kanban::class)
            ->call('updateBugStatus', $bug->id, 'closed');

        $this->assertDatabaseHas('bugs', [
            'id' => $bug->id,
            'status' => 'closed'
        ]);
    }

    public function test_project_with_multiple_bugs_workflow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        // Create multiple bugs with different statuses
        $openBug = Bug::factory()->create([
            'project_id' => $project->id,
            'status' => 'open',
            'severity' => 'critical'
        ]);

        $inProgressBug = Bug::factory()->create([
            'project_id' => $project->id,
            'status' => 'in_progress',
            'severity' => 'high'
        ]);

        $resolvedBug = Bug::factory()->create([
            'project_id' => $project->id,
            'status' => 'resolved',
            'severity' => 'medium'
        ]);

        // Verify project statistics
        $this->assertEquals(3, $project->fresh()->bugs->count());
        $this->assertEquals(1, $project->fresh()->bugs->where('status', 'open')->count());
        $this->assertEquals(1, $project->fresh()->bugs->where('status', 'in_progress')->count());
        $this->assertEquals(1, $project->fresh()->bugs->where('status', 'resolved')->count());

        // Move all bugs to resolved
        Livewire::test(Kanban::class)
            ->call('updateBugStatus', $openBug->id, 'resolved')
            ->call('updateBugStatus', $inProgressBug->id, 'resolved');

        // Verify all bugs are resolved
        $this->assertEquals(3, $project->fresh()->bugs->where('status', 'resolved')->count());
    }

    public function test_user_permissions_workflow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $project = Project::factory()->create(['created_by' => $admin->id]);

        // Admin can do everything
        $this->actingAs($admin);
        $this->assertTrue($admin->can('view-bugs'));
        $this->assertTrue($admin->can('create-bugs'));
        $this->assertTrue($admin->can('edit-bugs'));
        $this->assertTrue($admin->can('delete-bugs'));

        // Regular user has limited permissions
        $this->actingAs($user);
        $this->assertTrue($user->can('view-bugs'));
        $this->assertTrue($user->can('create-bugs'));
        $this->assertFalse($user->can('edit-bugs'));
        $this->assertFalse($user->can('delete-bugs'));
    }

    public function test_kanban_column_visibility_workflow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $kanban = Livewire::test(Kanban::class);

        // Initially all columns should be visible
        $this->assertTrue($kanban->get('visibleColumns.open'));
        $this->assertTrue($kanban->get('visibleColumns.in_progress'));
        $this->assertTrue($kanban->get('visibleColumns.testing'));
        $this->assertTrue($kanban->get('visibleColumns.resolved'));
        $this->assertTrue($kanban->get('visibleColumns.closed'));

        // Hide open column
        $kanban->call('toggleColumn', 'open');
        $this->assertFalse($kanban->get('visibleColumns.open'));

        // Hide all columns
        $kanban->call('hideAllColumns');
        $this->assertFalse($kanban->get('visibleColumns.open'));
        $this->assertFalse($kanban->get('visibleColumns.in_progress'));
        $this->assertFalse($kanban->get('visibleColumns.testing'));
        $this->assertFalse($kanban->get('visibleColumns.resolved'));
        $this->assertFalse($kanban->get('visibleColumns.closed'));

        // Show all columns
        $kanban->call('showAllColumns');
        $this->assertTrue($kanban->get('visibleColumns.open'));
        $this->assertTrue($kanban->get('visibleColumns.in_progress'));
        $this->assertTrue($kanban->get('visibleColumns.testing'));
        $this->assertTrue($kanban->get('visibleColumns.resolved'));
        $this->assertTrue($kanban->get('visibleColumns.closed'));
    }

    public function test_search_and_filter_workflow()
    {
        $user = User::factory()->create();
        $project1 = Project::factory()->create(['name' => 'Frontend Project']);
        $project2 = Project::factory()->create(['name' => 'Backend Project']);

        Bug::factory()->create([
            'title' => 'Login Button Issue',
            'project_id' => $project1->id,
            'severity' => 'high'
        ]);

        Bug::factory()->create([
            'title' => 'Database Connection Error',
            'project_id' => $project2->id,
            'severity' => 'critical'
        ]);

        $this->actingAs($user);

        // Test project filtering in Kanban
        $kanban = Livewire::test(Kanban::class);
        $kanban->set('project_id', $project1->id);

        // Should only show bugs from project1
        $bugs = $kanban->get('bugs');
        $this->assertTrue($bugs['open']->where('project_id', $project1->id)->count() > 0);
    }
}
