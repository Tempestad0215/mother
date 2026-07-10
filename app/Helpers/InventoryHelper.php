<?php

namespace App\Helpers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Collection;

class InventoryHelper
{

    /**
     * @param array<int,array{product_uuid:string, warehouse_uuid:string}> $productIds
     * @return Collection<Inventory>
     */
    public static function getInventoryProductWarehouse(array $productIds): Collection
    {
        return Inventory::where(
            function ($q1) use($productIds) {
                foreach ($productIds as $item) {
                    $q1->orWhere(function ($q2) use($item) {
                        $q2->where('product_uuid', $item['product_uuid'])
                        ->where('warehouse_uuid', $item['warehouse_uuid']);
                    });
                }
            }
        )->get()
            ->keyBy(fn(Inventory $item) => $item->product_uuid.'-'.$item->warehouse_uuid);
    }




}
