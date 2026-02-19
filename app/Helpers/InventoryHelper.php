<?php

namespace App\Helpers;

use App\Models\Inventory;
use Illuminate\Support\Collection;

class InventoryHelper
{

    /**
     * @param array<int, array{product_id:int, warehouse_id:int}> $infoProducts
     * @return Collection<Inventory>
     */
    public static function getInventoryProductWarehouse(array $infoProducts): Collection
    {


        return Inventory::where(function ($query) use ($infoProducts) {
            foreach ($infoProducts as $pair){
                $query->orWhere(function ($q) use($pair) {
                    $q->where('product_id', $pair['product_id'])
                        ->where('warehouse_id', $pair['warehouse_id']);
                });
            }
        })->get()
            ->keyBy(fn(Inventory $item) => $item->product_id.'-'.$item->warehouse_id);

    }

}
