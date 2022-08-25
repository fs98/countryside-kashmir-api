<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'persons' => fake()->numberBetween(1, 10),
            'adults'  => fake()->numberBetween(1, 10),
            'children'  => fake()->numberBetween(1, 10),
            'arrival_date' => fake()->dateTimeThisMonth(),
            'days' => fake()->numberBetween(1, 10),
            'nights' => fake()->numberBetween(1, 10),
            'package_id'  => Package::pluck('id')->random(),
            'user_id' => User::role('Client')->pluck('id')->random(),
        ];
    }
}
