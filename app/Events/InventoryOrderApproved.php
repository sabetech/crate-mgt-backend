<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\InvetoryOrder;

class InventoryOrderApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $inventoryOrder;
    public $action;
    /**
     * Create a new event instance.
     */
    public function __construct(InventoryOrder $inventoryOrder)
    {
        $this->inventoryOrder = $inventoryOrder;
        $this->action = 'approved_sale_order';
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
