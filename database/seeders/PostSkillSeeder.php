<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class PostSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        
        $skillIds = Skill::pluck('id')->toArray();

        Post::all()->each(function ($post) use ($skillIds) {
            if (!empty($skillIds)) {
                
                $count = rand(1, min(3, count($skillIds)));

               
                $randomSkills = collect($skillIds)->random($count);

                
                $post->skills()->sync($randomSkills);
            }
        });
    }
}
