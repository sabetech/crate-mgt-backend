<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Constants\EmptiesConstants;
use Log;

class ReturnProductToGGBL
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $emptiesReturningProductLog, $action;

    /**
     * Create a new event instance.
     */
    public function __construct($emptiesReturningProductLog)
    {
        //
        Log::info("DO you get here BL AB");
        Log::info($emptiesReturningProductLog);
        $this->emptiesReturningProductLog = $emptiesReturningProductLog;
        $this->action = EmptiesConstants::RETURNING_EMPTIES_TO_GGBL;
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
