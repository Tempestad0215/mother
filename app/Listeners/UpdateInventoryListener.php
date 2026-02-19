<?php

namespace App\Listeners;

use App\Events\StockDecreased;
use App\Models\Inventory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateInventoryListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StockDecreased $event): void
    {
        Inventory::where('product_id', $event->product_id)
            ->where('warehouse_id', $event->warehouse_id)
            ->decrement('qty_on_hand', $event->quantity);
    }
}
