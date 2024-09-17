<?php

namespace App\Helpers;

use App\Enums\ProductTransType;
use App\Models\ProTrans;
use Illuminate\Http\Request;

class TransHelper
{
    /**
     * Guardar los datos de la trans
     * @param Request $request
     * @param int $sale_id
     * @param int $product_id
     * @return void
     */
    public function store(Request $request, ProductTransType $type, int $sale_id = 0, int $product_id = 0):void
    {

        //Crear la transacion
        $proTrans = new ProTrans();
        $proTrans->product_id = $product_id;
        $proTrans->stock = $request->get('stock');
        $proTrans->sale_id = $sale_id;
        $proTrans->price = $request->get('price');
        $proTrans->cost = $request->get('cost');
        $proTrans->discount = $request->get('discount');
        $proTrans->discount_amount = $request->get('discount_amount');
        $proTrans->tax = $request->get('tax');
        $proTrans->amount = $request->get('amount');
        $proTrans->type = $type;
        $proTrans->save();

    }


    /**
     * @param Request $request
     * @param ProTrans $trans
     * @return void
     */
    public function update(Request $request, ProTrans $trans):void
    {
        $trans->product_id = $request->get('product_id');
        $trans->stock = $request->get('stock');
        $trans->price = $request->get('price');
        $trans->cost = $request->get('cost');
        $trans->sale_id = 0;
        $trans->discount = $request->get('discount');
        $trans->discount_amount = $request->get('discount_amount');
        $trans->tax = $request->get('tax');
        $trans->amount = $request->get('amount');
        $trans->type = ProductTransType::AJUSTE;
        $trans->save();
    }

}
