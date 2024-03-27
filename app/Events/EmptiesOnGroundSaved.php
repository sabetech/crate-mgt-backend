<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmptiesOnGroundProduct;
use Illuminate\Support\Facades\Log;

class EmptiesOnGroundSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $emptiesBalance;

    /**
     * Create a new event instance.
     */
    public function __construct(Array $emptiesBalance)
    {
        //
        Log::info($emptiesBalance);
        $this->emptiesBalance = $emptiesBalance;
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
