<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * PostFactory - Used to create test posts
 *
 * Helper methods are available to create posts with different statuses.
 *
 * Usage:
 * Post::factory()->create() - Draft post
 * Post::factory()->published()->create() - Published post
 * Post::factory()->pending()->create() - Pending post
 * Post::factory()->rejected()->create() - Rejected post
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Default state - Draft post
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(5, 10));

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5), // Unique slug
            'body' => fake()->paragraphs(rand(3, 6), true),
            'status' => 'draft',
            'rejection_reason' => null,
            'published_at' => null,
        ];
    }

    /**
     * Draft state
     * Usage: Post::factory()->draft()->create()
     */
    public function draft(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Pending state - Waiting for approval
     * Usage: Post::factory()->pending()->create()
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
            'published_at' => null,
        ]);
    }

    /**
     * Published state - Published
     * Usage: Post::factory()->published()->create()
     */
    public function published(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Rejected state - Rejected
     * Usage: Post::factory()->rejected()->create()
     */
    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'rejected',
            'rejection_reason' => fake()->sentence(),
            'published_at' => null,
        ]);
    }

    /**
     * Post for a specific user
     * Usage: Post::factory()->forUser($user)->create()
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
