<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\Task;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkLogFactory extends Factory
{
    public function definition(): array
    {
        $checkInTime = fake()->time('H:i', '09:00');
        $checkOutTime = fake()->time('H:i', '17:00');

        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'task_id' => null,
            'field_id' => Field::factory(),
            'log_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'check_in_latitude' => fake()->latitude(-1.5, 1.5),
            'check_in_longitude' => fake()->longitude(36, 38),
            'check_out_latitude' => fake()->latitude(-1.5, 1.5),
            'check_out_longitude' => fake()->longitude(36, 38),
            'activities_performed' => fake()->paragraph(),
            'notes' => fake()->sentence(),
            'weather_conditions' => fake()->randomElement(['Sunny', 'Cloudy', 'Rainy', 'Windy', 'Hot', 'Cool']),
            'hours_worked' => fake()->randomFloat(2, 4, 10),
            'status' => 'checked_out',
        ];
    }

    public function checkedIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'checked_in',
            'check_out_time' => null,
            'check_out_latitude' => null,
            'check_out_longitude' => null,
            'hours_worked' => null,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => User::factory(),
            'approved_at' => now(),
        ]);
    }

    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'log_date' => today(),
        ]);
    }

    public function withTask(): static
    {
        return $this->state(fn (array $attributes) => [
            'task_id' => Task::factory(),
        ]);
    }
}
