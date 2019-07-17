<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ConversationRemoved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    private $mid;
    private $cid;

    /**
     * Create a new event instance.
     *
     * @param $cid
     * @param $mid
     */

    public function __construct($cid, $mid)
    {
        $this->cid = $cid;
        $this->mid = $mid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $cid = $this->cid;
        $mid = $this->mid;

        return new PrivateChannel("c-$cid-$mid");
    }

    public function broadcastAs()
    {
        return 'rc';
    }
}
