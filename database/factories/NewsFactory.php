<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    use WithFaker;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'original_url' => $this->faker->url,
            'original_image_url' => $this->faker->imageUrl(),
            'author' => $this->faker->name,
            'source_id' => fn () => \App\Models\NewsSource::factory()->createOne()->id,
        ];
    }
}
