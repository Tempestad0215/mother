<?php

namespace App\Helpers;

use App\Enums\ProductTransType;
use App\Models\ProTrans;
use Illuminate\Http\Request;

class TransHelper
{
    /**
     * @param array $request
     * @param ProductTransType $type
     * @param int $sale_id
     * @param int $product_id
     * @param int $credit_note_id
     * @return void
     */
    public static function store(Array $request, ProductTransType $type, int $sale_id = 0, int $product_id = 0, int $credit_note_id = 0):void
    {
        
        //Crear el TransId
        $transId = $request['transID'] ?? null;

        //Verificar si existe la transcciones antiguia
        $transOld = ProTrans::find($transId);

        //Verificar si existe o no
        $proTrans = $transOld && $type == ProductTransType::RESERVA ? $transOld : new ProTrans();


        //Crear la transacion
        $proTrans->product_id = $product_id ?: $request['product_id'];
        $proTrans->product_name = $request["product_name"];
        $proTrans->reserved = $type === ProductTransType::RESERVA ?  $request["reserved"] : 0;
        $proTrans->stock = $request['stock'];
        $proTrans->sale_id = $sale_id ?: null;
        $proTrans->credit_note_id = $credit_note_id ?: null;
        $proTrans->price = $request['price'];
        $proTrans->discount = $request['discount'];
        $proTrans->discount_amount = $request['discount_amount'];
        $proTrans->tax_rate = $request['tax_rate'];
        $proTrans->tax = $request['tax'];
        $proTrans->amount = $request['amount'];
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
        $trans->sale_id = 0;
        $trans->discount = $request->get('discount');
        $trans->discount_amount = $request->get('discount_amount');
        $trans->tax = $request->get('tax');
        $trans->amount = $request->get('amount');
        $trans->type = ProductTransType::AJUSTE;
        $trans->save();
    }

}
