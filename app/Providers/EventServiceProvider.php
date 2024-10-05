<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\SalesOrderCreated;
use App\Events\InventoryOrderApproved;
use App\Events\InventoryTransactionCreated;
use App\Events\StockTakenForProduct;
use App\Events\InventoryReceivedFromGBL;
use App\Events\ReceivedProductFromGGBL;
use App\Events\LoadoutProductCreated;
use App\Events\ReturnProductToGGBL;
use App\Events\EmptiesTransactionCreated;
use App\Events\CustomerReturnEmpties;
use App\Listeners\UpdateInventoryPendingOrders;
use App\Listeners\UpdateInventoryTransactions;
use App\Listeners\UpdateCustomerEmptiesAfterInventoryTransaction;
use App\Listeners\UpdateProductBalanceAfterStockTaken;
use App\Listeners\UpdateProductBalanceAfterInventoryTransaction;
use App\Listeners\UpdateEmptiesLog;
use App\Listeners\UpdateEmptiesTransaction;
use App\Listeners\UpdateEmptiesBalance;


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
            UpdateInventoryTransactions::class,
            //UpdateCustomerEmptiesAfterInventoryTransaction::class,
            UpdateEmptiesTransaction::class //-- this will be done by the update inventory transaction event!!
        ],
        InventoryTransactionCreated::class => [
            UpdateProductBalanceAfterInventoryTransaction::class,
        ],
        StockTakenForProduct::class => [
            UpdateProductBalanceAfterStockTaken::class,
        ],
        ReturnProductToGGBL::class => [
            UpdateEmptiesTransaction::class,
        ],
        ReceivedProductFromGGBL::class => [
            UpdateInventoryTransactions::class,
        ],
        LoadoutProductCreated::class => [
            UpdateInventoryTransactions::class,
        ],
        EmptiesTransactionCreated::class => [
            UpdateEmptiesBalance::class,
        ],
        InventoryReceivedFromGBL::class => [
            UpdateEmptiesLog::class
        ],
        CustomerReturnEmpties::class => [
            UpdateEmptiesTransaction::class,
        ],
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
