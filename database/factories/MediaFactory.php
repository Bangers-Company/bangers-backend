<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => $this->faker->uuid(),
            'type' => $this->faker->randomElement(['profile_picture', 'artist_image', 'festival_banner']),
            'storage_key' => 'media/' . $this->faker->uuid() . '.jpg',
            'url' => $this->faker->imageUrl(),
            'mime_type' => 'image/jpeg',
            'size_bytes' => $this->faker->numberBetween(1000, 5000000),
            'is_public' => true,
            'metadata' => [],
        ];
    }
}
