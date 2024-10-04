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




    /**
     * @param Request $request
     * @return Paginator
     */
    public function get(Request $request):Paginator
    {
        //Obtner los datos de busqueda
        $search = $request->get('search');

        //Pasar los datos a la variable
        return Product::where('status', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like',"%$search%")
                    ->orWhereNull('name');
            })->where(function (Builder $builder){
                $builder->where(function (Builder $q){
                    $q->where('type', ProductTypeEnum::PRODUCTO)
                        ->where('stock', '>', 0);
                })->orWhere(function (Builder $p){
                   $p->where('type', ProductTypeEnum::SERVICIO);
                });
            })
            ->latest()
            ->simplePaginate(15);
    }





}
