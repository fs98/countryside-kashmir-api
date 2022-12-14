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
            'image' => fake()->imageUrl(),
            'image_alt' => fake()->sentence($nbWords = 3, $variableNbWords = true),
            'order' => fake()->numberBetween(0, 10),
            'title' => fake()->realText(32),
            'subtitle' => fake()->realText(16)
        ];
    }
}
