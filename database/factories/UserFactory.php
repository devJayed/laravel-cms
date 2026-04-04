<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * UserFactory - Used to create test users
 *
 * Factory Pattern allows easy generation of test data.
 * Default password for all users is 'password'.
 *
 * Usage:
 * User::factory()->create() - Random user
 * User::factory()->editor()->create() - Editor user
 * User::factory()->author()->create() - Author user
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Cache the default password for performance
     */
    protected static ?string $password;

    /**
     * Default state - Author role
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => 'author', // Default role
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Author state - Creates user with Author role
     * Usage: User::factory()->author()->create()
     */
    public function author(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'author',
        ]);
    }

    /**
     * Editor state - Creates user with Editor role
     * Usage: User::factory()->editor()->create()
     */
    public function editor(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'editor',
        ]);
    }

    /**
     * Admin state - Creates user with Admin role
     * Usage: User::factory()->admin()->create()
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Unverified state - Email unverified user
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
