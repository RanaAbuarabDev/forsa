<?php

namespace App\Services;

use App\Models\Post;

class PostFilterService
{





    public function filter(array $filters)
    {
        return Post::with(['governorate', 'experience', 'skills','user.profile'])
          
            ->when(!empty($filters['governorate_ids']), function ($query) use ($filters) {
                $query->whereHas('governorate', function ($q) use ($filters) {
                    $q->whereIn('id', $filters['governorate_ids']);
                });
            })

            
            ->when(!empty($filters['job_type']), function ($query) use ($filters) {
                $query->whereIn('job_type', $filters['job_type']);
            })

            
            ->when(!empty($filters['skill']), function ($query) use ($filters) {
                $query->whereHas('skills', function ($q) use ($filters) {
                    $q->whereIn('skills.name', (array) $filters['skill']);
                });
            })

            
            ->when(!empty($filters['experience']), function ($query) use ($filters) {
                $query->whereHas('experience', function ($q) use ($filters) {
                    $q->where('job_title', 'like', '%' . $filters['experience'] . '%');
                });
            })


            
            ->when(isset($filters['online']), function ($query) use ($filters) {
                $query->where('online', $filters['online']);
            })

        ->paginate(10); 
    }
}
