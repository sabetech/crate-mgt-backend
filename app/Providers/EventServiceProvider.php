<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\SalesOrderCreated;
use App\Events\InventoryOrderApproved;
use App\Events\InventoryTransactionCreated;
use App\Listeners\UpdateInventoryTransactionsAfterOrderApproval;
use App\Listeners\UpdateCustomerEmptiesAfterInvenetoryTransaction;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SalesOrderCreated::class => [
            UpdateInventoryPendingOrders::class,
        ],
        InventoryOrderApproved::class => [
            UpdateInventoryTransactionsAfterOrderApproval::class,
        ],
        InventoryTransactionCreated::class => [
            UpdateProductBalanceAfterInventoryTransaction::class,
            UpdateCustomerEmptiesAfterInvenetoryTransaction::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
