<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'title' => $this->data['title'] ?? '',
            'body' => $this->data['body'] ?? '',
            'post_id' => $this->data['post_id'] ?? null,
            'type' => $this->data['type'] ?? '',
            'user' => $this->data['user'] ?? null,
            'sent_at' => $this->created_at->diffForHumans(), 
            'read_at' => $this->read_at,
        ];
    }
}
