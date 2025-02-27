<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
//OLd
class EmptiesTransactionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $emptyTransaction;
    /**
     * Create a new event instance.
     */
    public function __construct($emptyTransaction)
    {
        //
        Log::Debug(["Empty Transaction Created" => $emptyTransaction]);
        $this->emptyTransaction = $emptyTransaction;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
