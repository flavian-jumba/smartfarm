<?php

namespace Database\Factories;

use App\Models\ExpenseCategory;
use App\Models\Field;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'field_id' => Field::factory(),
            'expense_category_id' => ExpenseCategory::factory(),
            'recorded_by' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'amount' => fake()->randomFloat(2, 100, 50000),
            'currency' => 'KES',
            'expense_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'vendor' => fake()->company(),
            'status' => 'pending',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => User::factory(),
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }
}
