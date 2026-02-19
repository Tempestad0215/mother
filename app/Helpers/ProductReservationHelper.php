<?php

namespace App\Helpers;

use App\Dtos\ProductInventoryDto;
use App\Dtos\ProductReservationDto;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductReservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductReservationHelper
{

    /**
     * @param array $data
     * @return void
     */
    public static function incrementReservationMultiple(array $data): void
    {
        self::checkArray($data);

        ProductReservation::insert($data);
    }


    /**
     * @param ProductReservationDto $data
     * @return void
     */
    public static function createReservation(ProductReservationDto $data):void
    {


        ProductReservation::create($data->toArray());
    }


    /**
     * @param array $data
     * @return void
     */
    private function checkArray(array $data): void
    {
        if(!isset($data))
        {
            throw ValidationException::withMessages(['No Existen Datos Para Reservas']);
        }

    }

    /**
     * @param ProductReservationDto[] $data
     * @return void
     */
    public static function deductReservationMultiple(array $data): void
    {
        self::checkArray($data);

        DB::transaction(function () use ($data) {
            $infoToSearch = collect($data)
                ->map(fn(ProductReservation $item ) => [
                    'product_id' => $item->product_id,
                    'sale_id' => $item->sale_id,
                    'warehouse_id' => $item->warehouse_id
                ])->toArray();
            $productIds = collect($data)
                ->map(fn(ProductReservation $item ) => $item->product_id)
                ->toArray();

            $productsReservations = self::getReservationProductWarehouse($infoToSearch);
            $products = ProductHelper::getProductsByIds($productIds);
            $productInventory = InventoryHelper::getInventoryProductWarehouse($infoToSearch);

            foreach ($data as $item) {
                $findIndexReserve = $item->product_id.'-'.$item->warehouse_id.'-'.$item->sale_id;
                $productId = $products[$item->product_id];
                $findIndexInventory = $item->product_id.'-'.$item->warehouse_id;


                if(
                    !isset($productsReservations[$findIndexReserve]) ||
                    !isset($products[$productId]) ||
                    !isset($productInventory[$findIndexInventory])) {
                    throw ValidationException::withMessages(['No Existe Datos Para Deducciones']);
                }

                /** @var ProductReservation $reserve */
                $reserve = $productsReservations[$findIndexReserve];
                /** @var Product $product */
                $product = $products[$productId];
                /** @var Inventory $inventory */
                $inventory = $productInventory[$findIndexInventory];

                if(!$product->is_service)
                {
                    $reserve->quantity -= $item->quantity;
                    $reserve->save();

//               Actualizamos tambien el invnetario de ese productos
                    $inventory->qty_on_hand += $item->quantity;
                    $inventory->committed -= $item->quantity;
                    $inventory->save();
                }
            }
        });



    }


    /**
     * @param array<int, array{product_id:int, warehouse_id:int, sale_id:int}> $data
     * @return Collection<string, ProductReservation>
     */
    public static function getReservationProductWarehouse(array $data): Collection
    {
        if (empty($data)) {
            return collect();
        }

        $query = ProductReservation::query();

        $query->where(function (Builder $q) use ($data) {
            foreach ($data as $item) {
                $q->orWhere(function (Builder $sub) use ($item) {
                    $sub->where('product_id', $item['product_id'])
                        ->where('warehouse_id', $item['warehouse_id'])
                        ->where('sale_id', $item['sale_id']);
                });
            }
        });

        return $query->get()
            ->keyBy(fn(ProductReservation $item) => $item->product_id . '-' . $item->warehouse_id . '-' . $item->sale_id);
    }
}
