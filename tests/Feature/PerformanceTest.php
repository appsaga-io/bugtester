<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Bug;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestSuite;

class PerformanceTest extends TestSuite
{
    use RefreshDatabase;

    public function test_kanban_performance_with_many_bugs()
    {
        $data = $this->createPerformanceTestData();
        $this->actingAs($data['admin']);

        $startTime = microtime(true);

        $response = $this->get(route('bugs.kanban'));

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        $this->assertLessThan(2.0, $executionTime, 'Kanban page should load in less than 2 seconds');
    }

    public function test_bug_index_performance_with_many_bugs()
    {
        $data = $this->createPerformanceTestData();
        $this->actingAs($data['admin']);

        $startTime = microtime(true);

        $response = $this->get(route('bugs.index'));

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        $this->assertLessThan(1.5, $executionTime, 'Bug index page should load in less than 1.5 seconds');
    }

    public function test_project_show_performance_with_many_bugs()
    {
        $data = $this->createPerformanceTestData();
        $this->actingAs($data['admin']);

        $startTime = microtime(true);

        $response = $this->get(route('projects.show', $data['project']));

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        $this->assertLessThan(1.0, $executionTime, 'Project show page should load in less than 1 second');
    }

    public function test_database_query_performance()
    {
        $data = $this->createPerformanceTestData();

        $startTime = microtime(true);

        // Test complex query performance
        $bugs = Bug::with(['project', 'reporter', 'assignee'])
            ->where('status', 'open')
            ->whereHas('project', function($query) {
                $query->where('status', 'active');
            })
            ->get();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $this->assertLessThan(0.5, $executionTime, 'Complex query should execute in less than 0.5 seconds');
        $this->assertGreaterThan(0, $bugs->count());
    }

    public function test_memory_usage_with_large_dataset()
    {
        $data = $this->createPerformanceTestData();
        $this->actingAs($data['admin']);

        $initialMemory = memory_get_usage();

        // Load all bugs with relationships
        $bugs = Bug::with(['project', 'reporter', 'assignee'])->get();

        $finalMemory = memory_get_usage();
        $memoryUsed = $finalMemory - $initialMemory;

        // Memory usage should be reasonable (less than 10MB)
        $this->assertLessThan(10 * 1024 * 1024, $memoryUsed, 'Memory usage should be less than 10MB');
        $this->assertGreaterThan(0, $bugs->count());
    }
}
