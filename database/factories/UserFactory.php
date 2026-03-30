<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'is_public' => true,
        ];
    }

    public function admin(): static
    {
        return $this->afterCreating(function (\App\Models\User $user) {
            $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin']);
            $user->roles()->syncWithoutDetaching([$adminRole->id]);
        });
    }
}
