<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JobBookedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $postId;

    public function __construct($user, $postId)
    {
        $this->user = $user;
        $this->postId = $postId;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'تم حجز عملك',
            'body'  => "قام '{$this->user->name}' بحجز العمل الذي نشرته، يمكنك متابعة تفاصيل الحجز من خلال صفحة 'منشوراتي'.",
            'post_id' => $this->postId,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'profile' => $this->user->profile?->toArray(),
            ],
            'type' => 'job_booked',
            'created_at' => now()->toDateTimeString(),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
