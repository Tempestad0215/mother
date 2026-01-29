<?php

namespace App\Helpers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelIdea\Helper\App\Models\_IH_Product_C;
use RuntimeException;
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
        $product->stock = $request->get('stock');
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
            $oldStock = Inventory::where('product_id', $product->id)
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
                ['product_id' => $product->id, 'warehouse_id' => $warehouse->id ?? null],
                [
                'product_id'   => $product->id,
                'warehouse_id' => $warehouse->id ?? null,
                'qty_on_hand'  => $newOnHand,
                'avg_cost'     => $newAvg,
            ]);

            $product->stock = ($product->stock ?? 0) + $quantity;
            $product->save();
        });
    }

    /**
     * Disminuye stock y registra un movimiento en Inventory.
     *
     * @param Product $product
     * @param Warehouse $warehouse
     * @param float $quantity
     * @return void
     * @throws RuntimeException|Throwable
     */
    public static function decrementStock(Product $product, Warehouse $warehouse, float $quantity): void
    {
        if ($quantity <= 0) {
            return;
        }

        DB::transaction(function () use ($product, $warehouse, $quantity) {
            $oldStock = Inventory::where('product_id', $product->id)
                ->latest('created_at')
                ->first();

            if (! $oldStock || ($oldStock->qty_on_hand ?? 0) < $quantity) {
                throw new RuntimeException('Stock insuficiente para el producto id=' . $product->id);
            }

            $newOnHand = $oldStock->qty_on_hand - $quantity;
            $avg = $oldStock->avg_cost ?? 0;


            Inventory::upsert(
                [
                    [
                        'product_id'   => $product->id,
                        'warehouse_id' => $warehouse->id ?? null,
                        'qty_on_hand'  => $newOnHand,
                        'avg_cost'     => $avg,
                    ],
                ],
                ['product_id', 'warehouse_id'], // columnas que definen el conflicto (unique by)
                ['qty_on_hand', 'avg_cost', 'updated_at'] // columnas a actualizar en conflicto
            );


            $product->stock = max(0, ($product->stock ?? 0) - $quantity);
            $product->save();
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
        $oldStock = Inventory::where('product_id', $product->id)
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
     * @return _IH_Product_C|LengthAwarePaginator|Product[]
     */

    public static function get(Request $request): _IH_Product_C|LengthAwarePaginator|array
    {
        $search  = trim((string) $request->get('search', ''));
        $perPage = (int) $request->get('perPage', 15);
        $stock   = $request->boolean('stock'); // true/false real

        $query = Product::query()
            ->where('status', true)
            ->when($search !== '', function (Builder $q) use ($search) {
                $q->where(function (Builder $qq) use ($search) {
                    $qq->where('name', 'LIKE', "%$search%")
                        ->orWhere('description', 'LIKE', "%$search%")
                        ->orWhere('sku', 'LIKE', "%$search%");
                });
            })
            ->when($stock, function (Builder $q) {
                // si stock=true: excluir servicios y exigir stock > 0
                $q->where('is_service', 0)->where('stock', '>', 0);
            }, function (Builder $q) {
                // si stock=false: permitir servicios o productos con stock
                $q->where(function (Builder $builder) {
                    $builder->where('is_service', 1)
                        ->orWhere(function (Builder $qq) {
                            $qq->where('is_service', 0)
                                ->where('stock', '>', 0);
                        });
                });
            });

        return $query->paginate($perPage);
    }

}
