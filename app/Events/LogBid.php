<?php

namespace App\Events;

use App\Events\LogBid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LogBid implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bidding;
    public function __construct($bidding)
    {
        $this->bidding = $bidding;
    }
    public function broadcastOn()
    {
        return new Channel('log-bid');
    }

    public function broadcastAs()
    {
        return 'bids';
    }
}
