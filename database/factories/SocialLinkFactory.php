<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialLink>
 */
class SocialLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       $platforms = ['facebook', 'instagram', 'whatsapp'];

    $platform = $this->faker->randomElement($platforms);

    $urls = [
        'facebook' => 'https://facebook.com/' . $this->faker->userName,
        'instagram' => 'https://instagram.com/' . $this->faker->userName,
        'whatsapp' => 'https://wa.me/' . $this->faker->numerify('##########'),
    ];

    return [
        
        'platform' => $platform,
        'url' => $urls[$platform],
    ];
    }
}
