<?php

namespace App\Helpers;

use App\Dtos\ProductInventoryDto;
use App\Enums\SaleTypeEnum;
use App\Models\Inventory;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Laravel\Octane\Exceptions\DdException;

class ProductInventoryHelper
{


    /**
     * @param array $data
     * @param Sale|null $sale
     * @return void
     */
    public static function decrementStockMultiple(array $data, ?Sale $sale = null):void
    {


        $productIds = collect($data)
            ->map(fn (ProductInventoryDto $item) =>[
                'product_id' => $item->product_id,
                'warehouse_id' => $item->warehouse_id,
            ])->toArray();


        $inventories = InventoryHelper::getInventoryProductWarehouse($productIds);


        foreach ($data as $item)
        {
            $findIndex = $item->product_id.'-'.$item->warehouse_id;

            if(!isset($inventories[$findIndex]))
            {
                throw ValidationException::withMessages(['Este Producto no Existe en el Listado']);
            }

            /** @var Inventory $inventory */
            $inventory = $inventories[$findIndex];


            if($sale !== null && $sale->type !== SaleTypeEnum::Cotizacion)
            {
                $inventory->qty_on_hand -= $item->qty_on_hand;
                if (!$sale->close_table) {
                    $inventory->committed += $item->committed;
                }


            }


            $inventory->save();

        }




    }

}
