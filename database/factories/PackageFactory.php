<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
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
            'days' => fake()->numberBetween(1, 10),
            'nights' => fake()->numberBetween(1, 10),
            'price' => fake()->numberBetween(6000, 30000),
            'category_id' => Category::pluck('id')->random(),
            'persons' => fake()->numberBetween(1, 10),
            'keywords' => fake()->words($nb = 3, $asText = true),
            'user_id' => User::role(['Super Admin', 'Admin'])->pluck('id')->random(),
            'author_id' => Author::pluck('id')->random(),
        ];
    }
}
