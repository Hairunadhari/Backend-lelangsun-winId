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
    public $event_lelang_id;
    public function __construct($button,$event_lelang_id)
    {
        $this->button = $button;
        $this->event_lelang_id = $event_lelang_id;
    }

    public function broadcastOn()
    {
        return new Channel('lelang');
    }

    public function broadcastAs()
    {
        return 'button-bid-event-'.$this->event_lelang_id;
    }
     
}
