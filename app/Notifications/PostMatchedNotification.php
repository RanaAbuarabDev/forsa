<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostMatchedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $post;

    public function __construct($post, $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $publisher = $this->post->user;
        $profile   = $publisher->profile;

        $skillNames      = $this->post->skills->pluck('name')->toArray();
        $experienceTitle = $this->post->experience?->job_title;

        $allKeywords = array_filter(array_merge($skillNames, [$experienceTitle]));
        $keywordsText = implode(', ', $allKeywords);

        $bodyText = "قام \"{$publisher->name}\" بنشر فرصة عمل تناسب اهتماماتك أو خبراتك في مجال : {$keywordsText}، لا تفوت هذه الفرصة.";

        return [
            'title' => 'فرصة عمل جديدة',
            'body'  => $bodyText,
            'post_id' => $this->post->id,
            'user' => [
                'id' => $publisher->id,
                'name' => $publisher->name,
                'profile' => $profile?->toArray(),
            ],
            'type' => 'job_creation',
            'created_at' => now()->toDateTimeString(),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
