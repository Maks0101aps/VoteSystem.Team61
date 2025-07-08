<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCommentAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    /**
     * Create a new event instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Отправляем уведомление в канал связанного объекта (петиции или голосования)
        return [
            new Channel('all-users'),
            new Channel($this->comment->commentable_type . '.' . $this->comment->commentable_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'comment.created';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->comment->id,
            'content' => $this->comment->content,
            'user_name' => $this->comment->user->name,
            'created_at' => $this->comment->created_at->diffForHumans(),
            'commentable_id' => $this->comment->commentable_id,
            'commentable_type' => $this->comment->commentable_type,
        ];
    }
} 