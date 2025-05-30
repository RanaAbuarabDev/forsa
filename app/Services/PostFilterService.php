<?php

namespace App\Services;

use App\Models\Post;

class PostFilterService
{
    public function filter(array $filters)
    {
        $query = Post::query()->with(['user.profile', 'governorate', 'skills', 'experience']);

        // فلترة حسب المحافظات
        if (!empty($filters['governorate_ids']) && is_array($filters['governorate_ids'])) {
            $query->whereIn('governorate_id', $filters['governorate_ids']);
        }

        // فلترة حسب نوع التوظيف
        if (!empty($filters['job_types']) && is_array($filters['job_types'])) {
            $query->whereIn('job_type', $filters['job_types']);
        }

        // فلترة حسب المهارة
        if (!empty($filters['skill'])) {
            $query->whereHas('skills', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['skill'] . '%');
            });
        }

        // فلترة حسب المسمى الوظيفي
        if (!empty($filters['experience'])) {
            $query->whereHas('experience', function ($q) use ($filters) {
                $q->where('job_title', 'like', '%' . $filters['experience'] . '%');
            });
        }

        // فلترة حسب العمل عن بعد
        if (isset($filters['online'])) {
            $query->where('online', $filters['online']);
        }

        return $query->latest()->paginate(20);
    }
}
