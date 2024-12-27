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

class InventoryReceivableCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $inventoryReceivable;
    public $activity;

    /**
     * Create a new event instance.
     */
    public function __construct($inventoryReceivable)
    {
        //
        $this->inventoryReceivable = $inventoryReceivable;
        $this->activity = InventoryConstants::PURCHASE_ORDER;
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
