<?php

namespace App\Helpers;

use App\Http\Resources\ProductTransResource;
use App\Models\Product;
use App\Models\ProTrans;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InHelper
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function getTransIn(Request $request): array
    {
        //tomar los datos de la busqueda
        $search = $request->get('search');

        //Extraer los datos
        $data = ProTrans::where('status', true)
            ->whereHas('product', function (Builder $query) use ($search) {
               $query->where('name', 'LIKE', '%'.$search.'%');
            })
            ->latest()
            ->simplePaginate(15);

        //Devolver los datos formateado
        return ProductTransResource::collection($data)->response()->getData(true);

    }


    /**
     * @param Request $request
     * @param Product $product
     * @return void
     */
    public function updateProduct(Request $request, Product $product):void
    {

        //Actulizar los datos
        $product->stock += $request->get('stock');

        $product->save();
        $this->updateGeneral($request, $product);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return void
     */
    public function adjustProduct(Request $request, Product $product):void
    {
        //Actulizar los datos
        $product->stock = $request->get('stock');
        $product->save();
        $this->updateProduct($request, $product);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return void
     */
    public function updateGeneral(Request $request, Product $product): void
    {
        $product->cost = $request->get('cost');
        $product->price = $request->get('price');
        $product->discount = $request->get('discount');
        $product->discount_amount = $request->get('discount_amount');
        $product->tax = $request->get('tax');
        $product->product_no_tax = $request->get('product_no_tax');
        $product->benefits = $request->get('benefits');
        $product->save();
    }
}
