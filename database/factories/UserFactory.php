<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'staf',
            'no_hp' => $this->faker->phoneNumber(),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'super_admin',
        ]);
    }

    public function kabag(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'kabag',
        ]);
    }

    public function kasubag(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'kasubag',
        ]);
    }

    public function staff(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'staf',
        ]);
    }

    public function guest(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'tamu',
        ]);
    }

    public function unverified(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}