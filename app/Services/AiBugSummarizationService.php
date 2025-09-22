<?php

namespace App\Services;

use App\Models\Bug;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiBugSummarizationService
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiUrl = 'https://api.openai.com/v1/chat/completions';
    }

    /**
     * Summarize bug information using AI
     */
    public function summarizeBug(Bug $bug): array
    {
        try {
            $prompt = $this->buildPrompt($bug);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert bug analysis assistant. Analyze the provided bug information and return a JSON response with the following structure: {"title": "concise bug title", "cause": "root cause analysis", "steps": "simplified reproduction steps", "severity": "low|medium|high|critical"}'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.3,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';

                // Try to parse JSON response
                $summary = json_decode($content, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $summary;
                } else {
                    // Fallback to basic parsing if JSON is malformed
                    return $this->parseFallbackResponse($content, $bug);
                }
            } else {
                Log::error('AI API request failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return $this->generateFallbackSummary($bug);
            }
        } catch (\Exception $e) {
            Log::error('AI summarization failed', [
                'error' => $e->getMessage(),
                'bug_id' => $bug->id
            ]);

            return $this->generateFallbackSummary($bug);
        }
    }

    /**
     * Build the prompt for AI analysis
     */
    private function buildPrompt(Bug $bug): string
    {
        $prompt = "Bug Information:\n";
        $prompt .= "Title: {$bug->title}\n";
        $prompt .= "Description: {$bug->description}\n";

        if ($bug->steps_to_reproduce) {
            $prompt .= "Steps to Reproduce: {$bug->steps_to_reproduce}\n";
        }

        if ($bug->log_data) {
            $prompt .= "Log Data: {$bug->log_data}\n";
        }

        $prompt .= "Current Severity: {$bug->severity}\n";
        $prompt .= "Current Priority: {$bug->priority}\n";
        $prompt .= "Project: {$bug->project->name}\n";

        $prompt .= "\nPlease analyze this bug and provide a JSON response with:";
        $prompt .= "\n- title: A concise, clear bug title (max 100 characters)";
        $prompt .= "\n- cause: Root cause analysis (max 200 characters)";
        $prompt .= "\n- steps: Simplified reproduction steps (max 300 characters)";
        $prompt .= "\n- severity: Recommended severity level (low, medium, high, critical)";

        return $prompt;
    }

    /**
     * Parse fallback response when JSON parsing fails
     */
    private function parseFallbackResponse(string $content, Bug $bug): array
    {
        return [
            'title' => $this->extractValue($content, 'title') ?: $bug->title,
            'cause' => $this->extractValue($content, 'cause') ?: 'Unable to determine root cause',
            'steps' => $this->extractValue($content, 'steps') ?: $bug->steps_to_reproduce ?: 'Steps not provided',
            'severity' => $this->extractValue($content, 'severity') ?: $bug->severity
        ];
    }

    /**
     * Extract value from text using simple pattern matching
     */
    private function extractValue(string $content, string $key): ?string
    {
        $pattern = "/{$key}['\"]?\s*[:=]\s*['\"]?([^'\",\n]+)['\"]?/i";
        if (preg_match($pattern, $content, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    /**
     * Generate fallback summary when AI is unavailable
     */
    private function generateFallbackSummary(Bug $bug): array
    {
        return [
            'title' => $bug->title,
            'cause' => 'Analysis pending - AI service unavailable',
            'steps' => $bug->steps_to_reproduce ?: 'Steps not provided',
            'severity' => $bug->severity
        ];
    }

    /**
     * Process automatic bug creation from log data
     */
    public function createBugFromLogs(array $logData, int $projectId): Bug
    {
        $summary = $this->analyzeLogData($logData);

        return Bug::create([
            'title' => $summary['title'],
            'description' => $summary['description'],
            'project_id' => $projectId,
            'severity' => $summary['severity'],
            'priority' => $summary['priority'],
            'reporter_id' => 1, // System user
            'source' => 'automatic',
            'log_data' => json_encode($logData),
            'ai_summary' => $summary,
        ]);
    }

    /**
     * Analyze log data to extract bug information
     */
    private function analyzeLogData(array $logData): array
    {
        $errorMessage = $logData['message'] ?? 'Unknown error';
        $stackTrace = $logData['stack_trace'] ?? '';
        $level = $logData['level'] ?? 'error';

        // Determine severity based on log level
        $severity = match(strtolower($level)) {
            'critical', 'fatal' => 'critical',
            'error' => 'high',
            'warning' => 'medium',
            'info', 'debug' => 'low',
            default => 'medium'
        };

        // Determine priority based on error frequency and type
        $priority = $this->determinePriority($logData);

        return [
            'title' => $this->generateTitleFromError($errorMessage),
            'description' => $this->generateDescriptionFromLogs($logData),
            'severity' => $severity,
            'priority' => $priority,
        ];
    }

    /**
     * Generate bug title from error message
     */
    private function generateTitleFromError(string $errorMessage): string
    {
        // Clean up error message and create a concise title
        $title = preg_replace('/\s+/', ' ', trim($errorMessage));
        $title = preg_replace('/[^\w\s\-\.]/', '', $title);

        return strlen($title) > 100 ? substr($title, 0, 97) . '...' : $title;
    }

    /**
     * Generate description from log data
     */
    private function generateDescriptionFromLogs(array $logData): string
    {
        $description = "Automatic bug report generated from application logs.\n\n";
        $description .= "Error: " . ($logData['message'] ?? 'Unknown error') . "\n";

        if (isset($logData['file'])) {
            $description .= "File: " . $logData['file'] . "\n";
        }

        if (isset($logData['line'])) {
            $description .= "Line: " . $logData['line'] . "\n";
        }

        if (isset($logData['context'])) {
            $description .= "Context: " . json_encode($logData['context']) . "\n";
        }

        return $description;
    }

    /**
     * Determine priority based on log data
     */
    private function determinePriority(array $logData): string
    {
        $level = strtolower($logData['level'] ?? 'error');
        $message = strtolower($logData['message'] ?? '');

        // High priority for critical errors
        if (in_array($level, ['critical', 'fatal']) ||
            str_contains($message, 'database') ||
            str_contains($message, 'connection')) {
            return 'urgent';
        }

        // Medium priority for errors
        if ($level === 'error') {
            return 'high';
        }

        // Low priority for warnings
        return 'medium';
    }
}
