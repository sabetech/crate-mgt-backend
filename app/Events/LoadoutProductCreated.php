<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Constants\InventoryConstants;
use Log;

class LoadoutProductCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $loadoutProduct, $action;
    /**
     * Create a new event instance.
     */
    public function __construct($loadoutProduct)
    {
        //
        Log::info("DO U actually reach here");
        $this->loadoutProduct = $loadoutProduct;
        $this->action = InventoryConstants::LOAD_OUT;
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
