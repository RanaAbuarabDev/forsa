<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\PostType;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $userName = $this->relationLoaded('user') && $this->user ? $this->user->name : null;
        $userImg = $this->relationLoaded('user') && $this->user && $this->user->relationLoaded('profile') ? $this->user->profile?->img : null;
        $user = $this->user;
        $base = [
            'id' => $this->id,
            'type' => $this->type,
            'description' => $this->description,
            'governorate' => $this->governorate->name,
            'skills' => $this->skills->pluck('name'),
            'job_type' => $this->job_type,
            'job_title' => $this->experience?->job_title,
            'created_at' => $this->created_at->diffForHumans(),
            'name' => $userName,
            'img'  =>  $userImg,
            
        ];

        if ($this->type === PostType::JobCreation->value) {
            $base += [
                'online'=>$this->online,
                'salary'=> $this->salary,
            ];

        }

        return $base;
    }
}
