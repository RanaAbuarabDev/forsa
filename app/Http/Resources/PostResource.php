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
            'created_at' => $this->created_at->diffForHumans(),
            'name' => $this->user->name,
            'img'=> $this->user->profile->img,
           
        ];

        if ($this->type === PostType::JobCreation->value) {
            $base += [
                'work_mode' => $this->work_mode,
                'job_type' => $this->job_type,
                'is_bookable' => $this->is_bookable,
                'salary'=> $this->salary,
            ];

        }

        return $base;
    }
}
