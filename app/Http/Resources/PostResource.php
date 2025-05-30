<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\PostType;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $base = [
            'id' => $this->id,
            'type' => $this->type,
            'description' => $this->description,
            'governorate' => $this->governorate->name,
            'skills' => $this->skills->pluck('name'),
            'job_type' => $this->job_type,
            'job_title' => $this->experience?->job_title,
            'created_at' => $this->created_at->diffForHumans(),
            'name' => $this->user->name,
            'img' => $this->user->profile?->img,
            
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
