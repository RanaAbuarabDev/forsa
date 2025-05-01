<?php

namespace App\Services;

use App\Models\Skill;
use Illuminate\Pagination\LengthAwarePaginator;

class SkillService
{
    public function getPostsBySkill($skillName): LengthAwarePaginator
    {
        // البحث عن المهارة
        $skill = Skill::where('name', $skillName)->first();

        if (!$skill) {
            throw new \Exception('Skill not found.');
        }

        // جلب البوستات المرتبطة بالمهارة
        return $skill->posts()->with(['skills', 'governorate', 'user.profile'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
    }
}

