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
    public $event_lelang_id;
    public $status;
    public function __construct($lot_item,$event_lelang_id,$status)
    {
        $this->lot_item = $lot_item;
        $this->event_lelang_id = $event_lelang_id;
        $this->status = $status;
    }

    // public function broadcastOn()
    // {
    //     return new Channel('next-lot');
    // }

    // public function broadcastAs()
    // {
    //     return 'lot';
    // }
    public function broadcastOn()
    {
        return new Channel('lelang');
    }

    public function broadcastAs()
    {
        return 'next-lot-event-'.$this->event_lelang_id;
    }
}
