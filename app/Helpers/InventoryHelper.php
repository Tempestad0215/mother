<?php

namespace App\Helpers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Collection;

class InventoryHelper
{

    /**
     * @param array<int,array{product_id:int, warehoue_id:int}> $productIds
     * @return Collection<Inventory>
     */
    public static function getInventoryProductWarehouse(array $productIds): Collection
    {
        return Inventory::where(
            function ($q1) use($productIds) {
                foreach ($productIds as $item) {
                    $q1->orWhere(function ($q2) use($item) {
                        $q2->where('product_id', $item['product_id'])
                        ->where('warehouse_id', $item['warehouse_id']);
                    });
                }
            }
        )->get()
            ->keyBy(fn(Inventory $item) => $item->product_id.'-'.$item->warehouse_id);
    }




}
