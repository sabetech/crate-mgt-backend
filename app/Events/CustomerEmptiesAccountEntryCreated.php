<?php

namespace App\Events;

use App\Constants\EmptiesConstants;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerEmptiesAccountEntryCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customerEmptiesAccountEntry;
    public $activity;

    /**
     * Create a new event instance.
     */
    public function __construct( $customerEmptiesAccountEntry )
    {

        $this->customerEmptiesAccountEntry = $customerEmptiesAccountEntry;

        if ($customerEmptiesAccountEntry->transaction_type === 'in') {
            $this->activity = EmptiesConstants::CUSTOMER_RETURN_EMPTIES;
        }else {
            $this->activity = EmptiesConstants::CUSTOMER_PURCHASE;
        }

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
