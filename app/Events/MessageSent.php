<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;
    
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn(): array
    {
        $recipient = $this->message->conversation->getOtherUser($this->message->sender_id);
        
        \Log::info('Broadcasting MessageSent', [
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'recipient_id' => $recipient->id
        ]);

        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
            new PrivateChannel('App.Models.User.' . $recipient->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->message->id,
            'body'       => $this->message->body,
            'sender_id'  => $this->message->sender_id,
            'sender'     => $this->message->sender->name,
            'created_at' => $this->message->created_at->format('H:i'),
        ];
    }
}
