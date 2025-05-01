<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'img' => $this->img ? asset('storage/' . $this->img) : null,
            'PhonNum' => $this->PhonNum,
            'bio' => $this->bio,
            'BD' => $this->BD,
            'age' => $this->BD ? Carbon::parse($this->BD)->age : null,
            'governorate' => $this->governorate?->name,
            'skills' => $this->skills->pluck('name'),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
