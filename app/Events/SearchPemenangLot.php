<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SearchPemenangLot implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bid;
    public function __construct($bid)
    {
        $this->bid = $bid;
    }

    public function broadcastOn()
    {
        return new Channel('search-pemenang-lot');
    }

    public function broadcastAs()
    {
        return 'pemenang-lot';
    }
}
