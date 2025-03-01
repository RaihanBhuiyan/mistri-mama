<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SmsEvenet
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $phone;
    public $text;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($phone, $text)
    {
        $this->phone = $phone;
        $this->text  = $text;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
