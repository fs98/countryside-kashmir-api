<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DestinationImage>
 */
class DestinationImageFactory extends Factory
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
            'image_alt' => fake()->sentence($nbWords = 6, $variableNbWords = true),
            'destination_id' => Destination::pluck('id')->random(),
            'user_id' => User::role(['Super Admin', 'Admin'])->pluck('id')->random(),
        ];
    }
}
