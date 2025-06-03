<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EducationalQualification>
 */
class EducationalQualificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       $educationLevels = ['PhD', 'Master', 'Higher Diploma', 'Diploma', 'Bachelor', 'High School', 'Middle School', 'None'];

        return [
            'profile_id' => \App\Models\Profile::factory(), 
            'education_level' => $this->faker->randomElement($educationLevels),
            'institution_name' => $this->faker->company(),
            'major' => $this->faker->word(),
            'graduation_date' => $this->faker->date(),
        ];
    }
}
