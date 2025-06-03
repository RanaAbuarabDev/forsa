<?php

namespace Database\Factories;

use App\Models\Governorate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
            return [
            'user_id' => User::factory(), 
            'governorate_id' => Governorate::inRandomOrder()->value('id'),
            'img' => fake()->imageUrl(),
            'BD' => fake()->date(),
            'residence_area' => fake()->city(),
            'gender' => fake()->randomElement(['male', 'female']),
            'employment_status' => fake()->randomElement(['employed', 'unemployed', 'seeking_better_opportunity']),
            'cv_path' => 'cvs/' . fake()->uuid() . '.pdf',
        ];
    }
}
