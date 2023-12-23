<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $from;
    public $to;
    public $message;
    public $channel;

    public function __construct($from, $to, $message, $channel)
    {
        $this->from = $from;
        $this->to = $to;
        $this->message = $message;
        $this->channel = $channel;
    }

    public function broadcastOn()
    {
        $name = [$this->from, $this->to];
        sort($name);

        return [join("-", $name)];
    }

    public function broadcastAs()
    {
        return 'my-event';
    }
}
