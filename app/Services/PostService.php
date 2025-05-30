<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PostService
{
    public function create(User $user, array $data): Post
    {
        return DB::transaction(function () use ($user, $data) {
            
            $skills = $data['skills'] ?? [];
            unset($data['skills']);

              
            $post = $user->posts()->create($data);

              
            if (!empty($skills)) {
                $post->skills()->attach($skills);
            }

            return $post;
        });
    }


    public function update(User $user, int $postId, array $data): Post{
        
        return DB::transaction(function () use ($user, $postId, $data) {
            $post = Post::findOrFail($postId);

            if ($post->user_id !== $user->id) {
                abort(403, 'Unauthorized');
            }

            $post->update($data);

            return $post->fresh();
        });
    }



    public function delete(User $user, int $postId): void{
        DB::transaction(function () use ($user, $postId) {
            $post = Post::findOrFail($postId);

            if ($post->user_id !== $user->id) {
                abort(403, 'Unauthorized');
            }

            $post->delete();
        });
    }


}
