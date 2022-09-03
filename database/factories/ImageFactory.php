<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $imageable = $this->commentable();

        return [
            'image' => fake()->imageUrl(),
            'image_alt' => fake()->sentence($nbWords = 3, $variableNbWords = true),
            'imageable_id' => $imageable::factory(),
            'imageable_type' => $imageable,
            'user_id' => User::role(['Super Admin', 'Admin'])->pluck('id')->random(),
        ];
    }

    public function commentable()
    {
        return $this->faker->randomElement([
            Destination::class,
        ]);
    }
}
