<?php

namespace App\Helpers;

use App\Dtos\InventoryMovementDto;
use App\Http\Resources\ProductResource;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Throwable;


class ProductHelper
{
    /**
     * @param Request $request
     * @param Product $product
     * @return void
     */
    public function update(Request $request, Product $product):void
    {

        $product->save();
    }



    /**
     * Incrementa stock y registra un movimiento en Inventory.
     *
     * @param Product $product
     * @param Warehouse $warehouse
     * @param float $quantity
     * @param float $cost
     * @return void
     * @throws Throwable
     */
    public static function incrementStock(Product $product, Warehouse $warehouse, float $quantity, float $cost): void
    {
        if ($quantity <= 0) {
            return;
        }

        DB::transaction(function () use ($product, $warehouse, $quantity, $cost) {
            $oldStock = Inventory::where('product_id', $product->uuid)
                ->latest('created_at')
                ->first();

            if ($oldStock) {
                $newAvg = self::getAvgCost($product, $quantity, $cost);
                $newOnHand = $oldStock->qty_on_hand + $quantity;
            } else {
                $newAvg = $cost;
                $newOnHand = $quantity;
            }

            Inventory::updateOrInsert(
                ['product_id' => $product->uuid, 'warehouse_id' => $warehouse->uuid ?? null],
                [
                'product_id'   => $product->uuid,
                'warehouse_id' => $warehouse->id ?? null,
                'qty_on_hand'  => $newOnHand,
                'avg_cost'     => $newAvg,
            ]);

            $product->save();
        });
    }

    /**
     * Disminuye stock y registra un movimiento en Inventory.
     *
     * @param InventoryMovementDto $data
     * @return void
     * @throws Throwable
     */
    public static function decrementStock(InventoryMovementDto $data): void
    {
        $qt = $data->quantity;

        if ($qt <= 0) {
            return;
        }

        DB::transaction(function () use ($data, $qt){

            $product = Product::find($data->product_id);

            if(!$product){
                throw ValidationException::withMessages([
                    'product_id' => 'No Existe Registro Con Este ID'
                ]);
            }

            $oldStock = Inventory::where('product_id', $data->product_id)
                ->where('warehouse_id', $data->warehouse_id)
                ->latest('created_at')
                ->first();

            if (!$oldStock || ($oldStock->qty_on_hand ?? 0) < $qt) {
                throw ValidationException::withMessages([
                    'warehouse_id' => "No Existen Registro Con Este id :".$data->product_id,
                ]);
            }

            $newOnHand = $oldStock->qty_on_hand - $qt;
            $avg = $oldStock->avg_cost ?? 0;

            Inventory::upsert(
                [
                    [
                        'product_id'   => $data->product_id,
                        'warehouse_id' => $data->warehouse_id ?? null,
                        'qty_on_hand'  => $newOnHand,
                        'avg_cost'     => $avg,
                    ],
                ],
                ['product_id', 'warehouse_id'], // columnas que definen el conflicto (unique by)
                ['qty_on_hand', 'avg_cost', 'updated_at'] // columnas a actualizar en conflicto
            );

//            TODO: Se debe verificar esta condicion

//            if($data->type !== InventoryMovementTypeEnum::Cotizacion)
//            {
//                $product->stock = max(0, ($product->stock ?? 0) - $qt);
//                $product->save();
//            }


            // Crear el movimiento de inventario
            $product->movements()->create([
                'type' => $data->type,
                'warehouse_id' => $data->warehouse_id,
                'quantity' => $data->quantity,
                'price' => $data->price,
                'cost' => $data->cost ?? $product->cost,
                'description' => $data->description,
            ]);
        });
    }


    /**
     * @param Product $product
     * @param float $quantity
     * @param float $cost
     * @return float
     * @throws Throwable
     */
    public static function getAvgCost(Product $product, float $quantity, float $cost):float
    {
        //Obtener los datos de oldStock
        $oldStock = Inventory::where('product_id', $product->uuid)
            ->latest('created_at')
            ->first();

        if (!$oldStock) {
            return $cost;
        }

        // Crear el cálculo para tomar el AVG de cost
        return (($oldStock->qty_on_hand * $oldStock->avg_cost) + ($quantity * $cost)) / ($oldStock->qty_on_hand + $quantity);

    }


    /**
     * @param Request $request
     * @param bool $stock
     * @return AnonymousResourceCollection
     */
    public static function get(Request $request, bool $stock = false): AnonymousResourceCollection
    {
        $search  = trim((string) $request->input('search', ''));
        $perPage = (int) $request->input('perPage', 15);

        $query = Product::query()
            ->with(['priceList', 'warehouses'])
            ->where('status', true)
            ->when($search !== '', function (Builder $q) use ($search) {
                $q->where(function (Builder $qq) use ($search) {
                    $qq->where('name', 'ILIKE', "%$search%")
                        ->orWhere('description', 'ILIKE', "%$search%")
                        ->orWhere('sku', 'ILIKE', "%$search%");
                });
            })
            ->when($stock, function (Builder $q) {
                // si stock=true: excluir servicios y exigir stock > 0
                $q->where('is_service', '=',0)
                    ->whereHas('warehouses', function (Builder $qq) {
                        $qq->where('warehouse_products.stock_quantity','>',0)
                            ->where('warehouse_products.is_active', true);
                    });

            });

        $paginatedData = $query->simplePaginate($perPage);



        return ProductResource::collection($paginatedData);
    }


    /**
     * @param array<int> $ids
     * @return Collection<Product>
     */
    public static function getProductsByIds(array $ids): Collection
    {
        return Product::whereIn('id', $ids)->get()->keyBy('id');
    }


    public static function getProductWithWarehouse(array $data)
    {
        return Product::whereIn('id', $data['product_id'])
            ->whereHas('inventory', function ($q1) use ($data) {
                $q1->whereIn('warehouse_id', $data['warehouse_id']);
            })
            ->get()->keyBy('id');
    }

}
