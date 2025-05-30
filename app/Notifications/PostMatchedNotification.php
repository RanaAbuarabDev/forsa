<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostMatchedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $post;
    /**
     * Create a new notification instance.
     */
    public function __construct($post,$user)
    {
        $this->post = $post;
        $this->user = $user;
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

    
    public function toDatabase($notifiable)
    {
        $publisher = $this->post->user;
        $profile = $publisher->profile;

        $skillNames = $this->post->skills->pluck('name')->toArray();
        $experienceTitle = $this->post->experience?->job_title;

        $allKeywords = array_filter(array_merge($skillNames, [$experienceTitle]));
        $keywordsText = implode(', ', $allKeywords);

        $bodyText = "قام \"{$publisher->name}\" بنشر فرصة عمل تناسب اهتماماتك أو خبراتك في مجال : {$keywordsText}، لا تفوت هذه الفرصة.";

        return [
            'title' => 'فرصة عمل جديدة',
            'body' => $bodyText,
            'post_id' => $this->post->id,
            'type' => 'job_creation',
            'user' => [
                'id' => $publisher->id,
                'name' => $publisher->name,
                'profile' => $profile?->toArray(),
            ],
            //'sent_at' => $this->created_at->diffForHumans()
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
            //
        ];
    }
}
