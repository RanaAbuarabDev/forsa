<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DesiredJob>
 */
class DesiredJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'profile_id' => Profile::factory(),  
            'job_title' => $this->faker->jobTitle(), 
            'job_location' => $this->faker->city(),  
            'employment_type' => $this->faker->randomElement([
            'full-time', 'part-time', 'freelance', 'volunteer', 'training', 'temporary', 'contracts'
            ]),
        ];
    }
}
