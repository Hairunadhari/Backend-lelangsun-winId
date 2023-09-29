<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NextLot implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lot_item;
    public function __construct($lot_item)
    {
        $this->lot_item = $lot_item;
    }

    public function broadcastOn()
    {
        return new Channel('next-lot');
    }

    public function broadcastAs()
    {
        return 'lot';
    }
}
