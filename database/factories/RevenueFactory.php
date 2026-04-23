<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RevenueFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->randomFloat(2, 10, 1000);
        $unitPrice = fake()->randomFloat(2, 50, 500);

        return [
            'tenant_id' => Tenant::factory(),
            'field_id' => Field::factory(),
            'recorded_by' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'source' => fake()->randomElement(['harvest_sale', 'livestock_sale', 'equipment_rental', 'subsidy', 'other']),
            'amount' => $quantity * $unitPrice,
            'currency' => 'KES',
            'quantity' => $quantity,
            'unit' => fake()->randomElement(['kg', 'bags', 'pieces', 'litres', 'crates']),
            'unit_price' => $unitPrice,
            'revenue_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'buyer_name' => fake()->name(),
            'buyer_contact' => fake()->phoneNumber(),
        ];
    }

    public function harvestSale(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => 'harvest_sale',
        ]);
    }
}
