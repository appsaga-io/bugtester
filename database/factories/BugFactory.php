<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bug>
 */
class BugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'severity' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'testing', 'resolved', 'closed']),
            'steps_to_reproduce' => $this->faker->paragraph(2),
            'project_id' => \App\Models\Project::factory(),
            'reporter_id' => \App\Models\User::factory(),
            'assigned_to' => $this->faker->optional()->randomElement(\App\Models\User::pluck('id')->toArray()),
        ];
    }
}
