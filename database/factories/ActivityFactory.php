<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => ucfirst(fake()->unique()->word()),
            'description' => json_encode(fake()->paragraphs(3, false)),
            'image' => fake()->imageUrl(),
            'image_alt' => fake()->sentence($nbWords = 3, $variableNbWords = true),
            'keywords' => fake()->words($nb = 3, $asText = true),
            'user_id' => User::role(['Super Admin', 'Admin'])->pluck('id')->random(),
            'author_id' => Author::pluck('id')->random(),
        ];
    }
}
