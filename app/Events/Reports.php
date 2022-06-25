<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;


class Reports implements  ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return ['stop', 'minus','analysis',
            'card', 'travel', 'car','direct',
            'quantity', 'loading', 'arrival',
            'stats', 'archive', 'home', 'management'];
    }

    public function broadcastAs()
    {
        return 'report';
    }
}