<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;


class JobBookedNotification extends Notification
{
    use Queueable;


    protected $user;
    protected $postId;
    /**
     * Create a new notification instance.
     */
    public function __construct($user, $postId)
    {
        $this->user = $user;
        $this->postId = $postId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return [
                'message' => "قام '{$this->user->name}' بحجز العمل الذي نشرته، يمكنك متابعة تفاصيل الحجز من خلال صفحة 'منشوراتي'.",
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
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
                
        ];
    }
}
