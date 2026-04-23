<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Field ' . fake()->randomNumber(3),
            'crop_type' => fake()->randomElement(['Wheat', 'Corn', 'Rice', 'Soybeans', 'Cotton', 'Barley', 'Potatoes', 'Tomatoes']),
            'planting_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'current_stage' => fake()->randomElement(['planted', 'growing', 'ready', 'harvested']),
            'agent_id' => User::factory(),
            'tenant_id' => Tenant::factory(),
        ];
    }

    /**
     * Indicate that the field is in planted stage.
     */
    public function planted(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_stage' => 'planted',
        ]);
    }

    /**
     * Indicate that the field is in growing stage.
     */
    public function growing(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_stage' => 'growing',
        ]);
    }

    /**
     * Indicate that the field is ready for harvest.
     */
    public function ready(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_stage' => 'ready',
        ]);
    }

    /**
     * Indicate that the field has been harvested.
     */
    public function harvested(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_stage' => 'harvested',
        ]);
    }
}
