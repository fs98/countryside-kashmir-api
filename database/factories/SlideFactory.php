<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slide>
 */
class SlideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'url' => fake()->imageUrl(),
            'order' => fake()->numberBetween(0, 10),
            'title' => fake()->realText(64),
            'subtitle' => fake()->realText(32)
        ];
    }
}
