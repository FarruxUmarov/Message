<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GotMessage implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message) {
    }

    public function broadcastOn() {
        $userId1 = auth()->id();
        $userId2 = $this->message->receiver_id;

        $channelName = $userId1 < $userId2
        ? "user_{$userId1}_to_{$userId2}"
        : "user_{$userId2}_to_{$userId1}";

        return new PrivateChannel('channelName');
    }
}
