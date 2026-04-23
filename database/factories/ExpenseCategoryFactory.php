<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseCategoryFactory extends Factory
{
    public function definition(): array
    {
        $categories = [
            ['name' => 'Seeds & Planting Materials', 'color' => '#22c55e'],
            ['name' => 'Fertilizers', 'color' => '#eab308'],
            ['name' => 'Pesticides & Herbicides', 'color' => '#ef4444'],
            ['name' => 'Equipment & Tools', 'color' => '#3b82f6'],
            ['name' => 'Labor', 'color' => '#8b5cf6'],
            ['name' => 'Irrigation', 'color' => '#06b6d4'],
            ['name' => 'Transportation', 'color' => '#f97316'],
            ['name' => 'Fuel', 'color' => '#64748b'],
            ['name' => 'Maintenance', 'color' => '#ec4899'],
            ['name' => 'Miscellaneous', 'color' => '#6b7280'],
        ];

        $category = fake()->randomElement($categories);

        return [
            'tenant_id' => Tenant::factory(),
            'name' => $category['name'],
            'description' => fake()->sentence(),
            'color' => $category['color'],
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
