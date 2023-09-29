<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StartBid implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $button;
    public function __construct($button)
    {
        $this->button = $button;
    }

    public function broadcastOn()
    {
        return new Channel('button');
    }

    public function broadcastAs()
    {
        return 'respon-button';
    }
}
