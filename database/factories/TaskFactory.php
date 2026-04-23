<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'field_id' => Field::factory(),
            'assigned_to' => User::factory(),
            'assigned_by' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(['planting', 'watering', 'fertilizing', 'pest_control', 'harvesting', 'maintenance', 'inspection', 'other']),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => 'pending',
            'due_date' => fake()->dateTimeBetween('now', '+2 weeks'),
            'target_latitude' => fake()->latitude(-1.5, 1.5),
            'target_longitude' => fake()->longitude(36, 38),
            'gps_tolerance_meters' => 100,
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'started_at' => now()->subHours(2),
            'completed_at' => now(),
            'completion_notes' => fake()->sentence(),
            'gps_verified' => true,
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
            'due_date' => now()->addDay(),
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'due_date' => now()->subDays(3),
        ]);
    }
}
