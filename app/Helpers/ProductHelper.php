<?php

namespace App\Helpers;

use App\Enums\ProductTypeEnum;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use LaravelIdea\Helper\App\Models\_IH_Product_C;


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
     * @param Product $product
     * @param float $quantity
     * @param float $cost
     * @return float
     * @throws \Throwable
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
     * @return Paginator|_IH_Product_C
     */
    public function get(Request $request):Paginator|_IH_Product_C
    {
        //Obtuser los datos de búsqueda
        $search = $request->get('search','');
        $perPage = $request->get('perPage',15);
        $stock = $request->get('stock',false);

        //Pasar los datos a la variable
        $query = Product::where('status', true)
            ->where(function ($query) use (&$search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%')
                    ->orWhere('sku', 'LIKE', '%' . $search . '%');
            })
            ->where(function (Builder $builder) {
                $builder->where('type', ProductTypeEnum::Servicio)
                    ->orWhere(function (Builder $query) {
                        $query->where('type', ProductTypeEnum::Producto)
                        ->where('stock', '>', 0);
                    });
            });
        //Devolver los resultado
        return $query->simplePaginate($perPage);
    }
}
