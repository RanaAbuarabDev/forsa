<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{

    protected $model = Post::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['job_request', 'job_creation']);

        return [
            'type' => $type,
            'description' => $this->faker->paragraphs(3, true),
            'user_id' => User::factory(),
            'governorate_id' => $this->faker->numberBetween(1, 14),
            'experience_id' => Experience::inRandomOrder()->value('id'),

            'job_type' => $type === 'job_creation'
                ? $this->faker->randomElement(['training', 'volunteer','temporary','full-time','part-time','contracts','free-work'])
                : null,

            'online' => $type === 'job_creation'
                ? $this->faker->boolean()
                : null,

            'salary' => $type === 'job_creation'
                ? $this->faker->numberBetween(300, 5000) . ' $'
                : null,
        ];
    }
}
