<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReplyNotification extends Notification
{
    use Queueable;

    public function __construct(public Reply $reply)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'new_reply',
            'reply_id'     => $this->reply->id,
            'post_id'      => $this->reply->post_id,
            'post_title'   => $this->reply->post->title,
            'user_name'    => $this->reply->user->name,
            'message'      => "{$this->reply->user->name} replied to your topic \"{$this->reply->post->title}\"",
            'url'          => route('posts.show', $this->reply->post) . '#reply-' . $this->reply->id,
        ];
    }
}
