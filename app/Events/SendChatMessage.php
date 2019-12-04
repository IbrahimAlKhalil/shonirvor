<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $msg;
    private $cid;
    private $mid;

    /**
     * Create a new event instance.
     *
     * @param ChatMessage $message
     */

    public function __construct(ChatMessage $message)
    {
        $this->msg = [
            'at' => $message->created_at->toDateTimeString(),
            'id' => $message->id,
            'mid' => $message->conversation_member_id,
            'msg' => $message->message
        ];
        $this->cid = $message->conversation_id;
        $this->mid = $message->conversation_member_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $cid = $this->cid;

        return new PresenceChannel("c-$cid");
    }

    public function broadcastAs()
    {
        return 'm';
    }
}
