<?php

namespace Tests\Unit\Models;

use App\Models\Project;
use App\Models\User;
use App\Models\Bug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_can_be_created()
    {
        $project = Project::factory()->create([
            'name' => 'Test Project',
            'description' => 'This is a test project',
            'status' => 'active'
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'description' => 'This is a test project',
            'status' => 'active'
        ]);
    }

    public function test_project_belongs_to_creator()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['created_by' => $user->id]);

        $this->assertEquals($user->id, $project->creator->id);
    }

    public function test_project_has_many_bugs()
    {
        $project = Project::factory()->create();
        $bug1 = Bug::factory()->create(['project_id' => $project->id]);
        $bug2 = Bug::factory()->create(['project_id' => $project->id]);

        $this->assertTrue($project->bugs->contains($bug1));
        $this->assertTrue($project->bugs->contains($bug2));
        $this->assertEquals(2, $project->bugs->count());
    }

    public function test_project_has_status_colors()
    {
        $project = Project::factory()->create(['status' => 'active']);
        $this->assertEquals('success', $project->status_color);

        $project = Project::factory()->create(['status' => 'completed']);
        $this->assertEquals('primary', $project->status_color);

        $project = Project::factory()->create(['status' => 'on_hold']);
        $this->assertEquals('warning', $project->status_color);
    }

    public function test_project_can_be_completed()
    {
        $project = Project::factory()->create(['status' => 'active']);

        $project->complete();

        $this->assertEquals('completed', $project->status);
    }

    public function test_project_can_be_put_on_hold()
    {
        $project = Project::factory()->create(['status' => 'active']);

        $project->putOnHold();

        $this->assertEquals('on_hold', $project->status);
    }

    public function test_project_scope_by_status()
    {
        Project::factory()->create(['status' => 'active']);
        Project::factory()->create(['status' => 'completed']);
        Project::factory()->create(['status' => 'on_hold']);

        $activeProjects = Project::byStatus('active')->get();
        $this->assertEquals(1, $activeProjects->count());
    }

    public function test_project_bug_count()
    {
        $project = Project::factory()->create();
        Bug::factory()->create(['project_id' => $project->id]);
        Bug::factory()->create(['project_id' => $project->id]);

        $this->assertEquals(2, $project->bug_count);
    }

    public function test_project_open_bug_count()
    {
        $project = Project::factory()->create();
        Bug::factory()->create(['project_id' => $project->id, 'status' => 'open']);
        Bug::factory()->create(['project_id' => $project->id, 'status' => 'resolved']);

        $this->assertEquals(1, $project->open_bug_count);
    }
}
