<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmergencyAlertFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tenant_id' => Tenant::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(['medical', 'security', 'equipment', 'weather', 'pest_outbreak', 'other']),
            'severity' => fake()->randomElement(['low', 'medium', 'high', 'critical']),
            'status' => 'pending',
            'latitude' => fake()->latitude(-1.5, 1.5),
            'longitude' => fake()->longitude(36, 38),
        ];
    }

    public function critical(): static
    {
        return $this->state(fn (array $attributes) => [
            'severity' => 'critical',
        ]);
    }

    public function acknowledged(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by' => User::factory(),
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_notes' => fake()->sentence(),
        ]);
    }
}
