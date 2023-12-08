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

    public $pemenang_bid;
    public $event_lelang_id;
    public function __construct($pemenang_bid,$event_lelang_id)
    {
        $this->pemenang_bid = $pemenang_bid;
        $this->event_lelang_id = $event_lelang_id;
    }

    public function broadcastOn()
    {
        return new Channel('lelang');
    }

    public function broadcastAs()
    {
        return 'pemenang-lot-event-'.$this->event_lelang_id;
    }
    // public function broadcastOn()
    // {
    //     return new Channel('lelang');
    // }

    // public function broadcastAs()
    // {
    //     return 'pemenang-lot';
    // }
}
