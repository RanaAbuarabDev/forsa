<?php




namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\DesiredJob;
use App\Models\EducationalQualification;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $this->call([
            GovernorateSeeder::class,
            SkillSeeder::class,
            ExperienceSeeder::class,
            CertificateSeeder::class,
            PostSeeder::class,
        ]);

        Certificate::factory(10)->create();

        Profile::factory(10)->create()->each(function ($profile) {
            
            $profile->skills()->attach(
                Skill::inRandomOrder()->take(rand(2, 4))->pluck('id')
            );

            $profile->experiences()->attach(
                Experience::inRandomOrder()->take(rand(1, 3))->pluck('id')->mapWithKeys(function ($id) {
                    return [$id => [
                        'years_of_experience' => rand(1, 10),
                        'job_description' => fake()->sentence()
                    ]];
                })->toArray()
            );

            DesiredJob::factory()->create(['profile_id' => $profile->id]);

            EducationalQualification::factory()->create(['profile_id' => $profile->id]);

           
            $platforms = ['facebook', 'instagram', 'whatsapp'];
            $platformsToUse = collect($platforms)->random(rand(1, count($platforms)));

            foreach ($platformsToUse as $platform) {
                SocialLink::factory()->create([
                    'profile_id' => $profile->id,
                    'platform' => $platform,
                    'url' => match ($platform) {
                        'facebook' => 'https://facebook.com/' . fake()->userName(),
                        'instagram' => 'https://instagram.com/' . fake()->userName(),
                        'whatsapp' => 'https://wa.me/' . fake()->numerify('##########'),
                    },
                ]);
            }

            $profile->certificates()->attach(
                Certificate::inRandomOrder()->take(rand(1, 3))->pluck('id')->mapWithKeys(function ($id) {
                    return [$id => ['issued_at' => now()->subYears(rand(1, 5))]];
                })->toArray()
            );
        });

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}

