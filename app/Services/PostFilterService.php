<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Governorate;

class PostFilterService
{
    public function filter(array $filters)
    {
        $query = Post::query()->with(['skills', 'governorate', 'user.profile']);

        if (isset($filters['governorate_id'])) {
            $governorateId = $filters['governorate_id'];

            if (!Governorate::where('id', $governorateId)->exists()) {
                throw new \Exception('Governorate not found.', 404);
            }

            $query->where('governorate_id', $governorateId);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }
}

