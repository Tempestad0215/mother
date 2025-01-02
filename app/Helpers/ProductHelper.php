<?php

namespace App\Helpers;

use App\Enums\ProductTypeEnum;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;


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


    public function get(Request $request)
    {
        //Obtuser los datos de búsqueda
        $search = $request->get('search','');
        $perPage = $request->get('perPage',15);

        //Pasar los datos a la variable
        $data = Product::where('status', true)
            ->where(function ($query) use (&$search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%')
                    ->orWhere('sku', 'LIKE', '%' . $search . '%');
            })->simplePaginate($perPage);

//            ->where(function (Builder $builder) {
//                $builder->where('type', ProductTypeEnum::SERVICIO)
//                    ->orWhere(function (Builder $query) {
//                        $query->where('type', ProductTypeEnum::PRODUCTO)
//                            ->where('stock','>',0);
//                    });
//            })->paginate();


        dd($data);


    }




}
