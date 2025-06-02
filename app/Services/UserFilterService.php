<?php

namespace App\Services;

use App\Models\User;

class UserFilterService
{
    public function filter(array $filters)
    {
        $query = User::with(['profile.governorate', 'profile.experiences', 'profile.skills']);

        
        if (!empty($filters['governorates'])) {
            $query->whereHas('profile', function ($q) use ($filters) {
                $q->whereIn('governorate_id', $filters['governorates']);
            });
        }

       
        if (!empty($filters['experience'])) {
            $query->whereHas('profile.experiences', function ($q) use ($filters) {
                $q->where('job_title', 'like', '%' . $filters['experience'] . '%');
            });
        }

        
        if (!empty($filters['skill'])) {
            $query->whereHas('profile.skills', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['skill'] . '%');
            });
        }

        return $query->paginate(10);
    }
}
