<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollFactory extends Factory
{
    public function definition(): array
    {
        $baseAmount = fake()->randomFloat(2, 10000, 100000);
        $bonusAmount = fake()->randomFloat(2, 0, 10000);
        $deductions = fake()->randomFloat(2, 0, 5000);

        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'processed_by' => User::factory(),
            'period' => now()->format('F Y'),
            'payment_type' => fake()->randomElement(['salary', 'wages', 'bonus', 'overtime', 'commission']),
            'base_amount' => $baseAmount,
            'bonus_amount' => $bonusAmount,
            'deductions' => $deductions,
            'net_amount' => $baseAmount + $bonusAmount - $deductions,
            'currency' => 'KES',
            'notes' => fake()->sentence(),
            'status' => 'pending',
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'payment_date' => now(),
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }
}
