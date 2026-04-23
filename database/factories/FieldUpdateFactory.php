<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\FieldUpdate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FieldUpdate>
 */
class FieldUpdateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'field_id' => Field::factory(),
            'agent_id' => User::factory(),
            'stage' => fake()->randomElement(['planted', 'growing', 'ready', 'harvested']),
            'notes' => fake()->optional(0.8)->sentence(),
        ];
    }

    /**
     * Indicate that the update has a note.
     */
    public function withNotes(): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => fake()->paragraph(),
        ]);
    }
}
