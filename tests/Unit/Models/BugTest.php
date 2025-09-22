<?php

namespace Tests\Unit\Models;

use App\Models\Bug;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BugTest extends TestCase
{
    use RefreshDatabase;

    public function test_bug_can_be_created()
    {
        $bug = Bug::factory()->create([
            'title' => 'Test Bug',
            'description' => 'This is a test bug',
            'severity' => 'high',
            'priority' => 'urgent',
            'status' => 'open'
        ]);

        $this->assertDatabaseHas('bugs', [
            'title' => 'Test Bug',
            'description' => 'This is a test bug',
            'severity' => 'high',
            'priority' => 'urgent',
            'status' => 'open'
        ]);
    }

    public function test_bug_belongs_to_reporter()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create(['reporter_id' => $user->id]);

        $this->assertEquals($user->id, $bug->reporter->id);
    }

    public function test_bug_belongs_to_assignee()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create(['assigned_to' => $user->id]);

        $this->assertEquals($user->id, $bug->assignee->id);
    }

    public function test_bug_belongs_to_project()
    {
        $project = Project::factory()->create();
        $bug = Bug::factory()->create(['project_id' => $project->id]);

        $this->assertEquals($project->id, $bug->project->id);
    }

    public function test_bug_has_severity_colors()
    {
        $bug = Bug::factory()->create(['severity' => 'critical']);
        $this->assertEquals('danger', $bug->severity_color);

        $bug = Bug::factory()->create(['severity' => 'high']);
        $this->assertEquals('warning', $bug->severity_color);

        $bug = Bug::factory()->create(['severity' => 'medium']);
        $this->assertEquals('info', $bug->severity_color);

        $bug = Bug::factory()->create(['severity' => 'low']);
        $this->assertEquals('success', $bug->severity_color);
    }

    public function test_bug_has_status_colors()
    {
        $bug = Bug::factory()->create(['status' => 'open']);
        $this->assertEquals('danger', $bug->status_color);

        $bug = Bug::factory()->create(['status' => 'in_progress']);
        $this->assertEquals('primary', $bug->status_color);

        $bug = Bug::factory()->create(['status' => 'testing']);
        $this->assertEquals('warning', $bug->status_color);

        $bug = Bug::factory()->create(['status' => 'resolved']);
        $this->assertEquals('success', $bug->status_color);

        $bug = Bug::factory()->create(['status' => 'closed']);
        $this->assertEquals('secondary', $bug->status_color);
    }

    public function test_bug_can_be_resolved()
    {
        $bug = Bug::factory()->create(['status' => 'open']);

        $bug->resolve();

        $this->assertEquals('resolved', $bug->status);
        $this->assertNotNull($bug->resolved_at);
    }

    public function test_bug_can_be_closed()
    {
        $bug = Bug::factory()->create(['status' => 'resolved']);

        $bug->close();

        $this->assertEquals('closed', $bug->status);
    }

    public function test_bug_scope_by_status()
    {
        Bug::factory()->create(['status' => 'open']);
        Bug::factory()->create(['status' => 'in_progress']);
        Bug::factory()->create(['status' => 'resolved']);

        $openBugs = Bug::byStatus('open')->get();
        $this->assertEquals(1, $openBugs->count());
    }

    public function test_bug_scope_by_severity()
    {
        Bug::factory()->create(['severity' => 'critical']);
        Bug::factory()->create(['severity' => 'high']);
        Bug::factory()->create(['severity' => 'medium']);

        $criticalBugs = Bug::bySeverity('critical')->get();
        $this->assertEquals(1, $criticalBugs->count());
    }
}
