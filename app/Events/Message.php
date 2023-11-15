<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Message implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $email;
    public $harga_bidding;
    public $id_event;

    public function __construct($email, $harga_bidding, $id_event)
    {
        $this->email = $email;
        $this->harga_bidding = $harga_bidding;
        $this->id_event = $id_event;
    }

    public function broadcastOn()
    {
        return new Channel('bid-event.'.$this->id_event);
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
