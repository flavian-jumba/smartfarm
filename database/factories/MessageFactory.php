<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'subject' => fake()->sentence(4),
            'body' => fake()->paragraphs(2, true),
            'type' => fake()->randomElement(['text', 'alert', 'report', 'request', 'broadcast']),
            'priority' => fake()->randomElement(['low', 'normal', 'high', 'urgent']),
        ];
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => now(),
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
        ]);
    }

    public function alert(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'alert',
            'priority' => 'high',
        ]);
    }

    public function reply(int $parentId): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parentId,
            'subject' => null,
        ]);
    }
}
