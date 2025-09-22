<?php

namespace Tests\Unit\Services;

use App\Services\AiBugSummarizationService;
use App\Models\Bug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class AiBugSummarizationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_summarize_bug()
    {
        $bug = Bug::factory()->create([
            'title' => 'Login button not working',
            'description' => 'The login button on the homepage does not respond when clicked. This happens on all browsers.',
            'severity' => 'high',
            'priority' => 'urgent'
        ]);

        $service = new AiBugSummarizationService();
        $summary = $service->summarize($bug);

        $this->assertIsString($summary);
        $this->assertNotEmpty($summary);
    }

    public function test_summary_contains_key_information()
    {
        $bug = Bug::factory()->create([
            'title' => 'Critical Database Error',
            'description' => 'Database connection fails when user tries to save data',
            'severity' => 'critical',
            'priority' => 'urgent'
        ]);

        $service = new AiBugSummarizationService();
        $summary = $service->summarize($bug);

        $this->assertStringContainsString('Database', $summary);
        $this->assertStringContainsString('Critical', $summary);
    }

    public function test_handles_empty_description()
    {
        $bug = Bug::factory()->create([
            'title' => 'Simple Bug',
            'description' => '',
            'severity' => 'low'
        ]);

        $service = new AiBugSummarizationService();
        $summary = $service->summarize($bug);

        $this->assertIsString($summary);
        $this->assertNotEmpty($summary);
    }

    public function test_handles_long_description()
    {
        $longDescription = str_repeat('This is a very long description. ', 50);

        $bug = Bug::factory()->create([
            'title' => 'Long Description Bug',
            'description' => $longDescription,
            'severity' => 'medium'
        ]);

        $service = new AiBugSummarizationService();
        $summary = $service->summarize($bug);

        $this->assertIsString($summary);
        $this->assertLessThan(strlen($longDescription), strlen($summary));
    }

    public function test_summary_is_deterministic()
    {
        $bug = Bug::factory()->create([
            'title' => 'Test Bug',
            'description' => 'This is a test bug description',
            'severity' => 'high'
        ]);

        $service = new AiBugSummarizationService();
        $summary1 = $service->summarize($bug);
        $summary2 = $service->summarize($bug);

        $this->assertEquals($summary1, $summary2);
    }
}
