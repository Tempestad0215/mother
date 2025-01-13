<?php

namespace App\Helpers;

use App\Enums\PROTYEnum;
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
    public function update(Request $request, PRoduct $product):void
    {
        $product->stock = $request->get('stock');
        $product->save();
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

        //Pasar los datos a la variable
        return Product::where('status', true)
            ->where(function ($query) use (&$search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%')
                    ->orWhere('sku', 'LIKE', '%' . $search . '%');
            })
            ->where(function (Builder $builder) {
                $builder->where('type', PROTYEnum::SERVICIO)
                    ->orWhere(function (Builder $query) {
                        $query->where('type', PROTYEnum::PRODUCTO)
                            ->where('stock','>',0);
                    });
            })->simplePaginate($perPage);

    }




}
