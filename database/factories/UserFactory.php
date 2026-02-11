<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'name' => fake()->name(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function cashier(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'cashier',
        ]);
    }

    public function sales(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'sales',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
