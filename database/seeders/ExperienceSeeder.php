<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Experience;
class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobTitles = [
            'Software Engineer',
            'Frontend Developer',
            'Backend Developer',
            'Full Stack Developer',
            'Data Analyst',
            'Project Manager',
            'UX/UI Designer',
            'DevOps Engineer',
            'Mobile App Developer',
            'QA Tester',
            'Product Manager',
            'IT Support Specialist',
        ];

        foreach ($jobTitles as $title) {
            Experience::create([
                'job_title' => $title,
            ]);
        }
    }
}
