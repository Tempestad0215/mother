<?php

namespace App\Helpers;

use App\Enums\PROTRTYEnum;
use App\Models\ProTrans;
use Illuminate\Http\Request;

class TransHelper
{
    /**
     * @param array $request
     * @param PROTRTYEnum $type
     * @param string $sale_id
     * @param string $product_id
     * @param string $credit_note_id
     * @return void
     */
    public static function store(Array $request, PROTRTYEnum $type, string $sale_id = "", string $product_id = "", string $credit_note_id = ""):void
    {

        //Crear el TransId
        $transId = $request['transID'] ?? null;

        //Verificar si existe la transcciones antiguia
        $transOld = ProTrans::find($transId);

        //Verificar si existe o no
        $proTrans = $transOld && $type == PROTRTYEnum::RESERVA ? $transOld : new ProTrans();

        //Crear la transacion
        $proTrans->product_id = $product_id ?: $request['product_id'];
        $proTrans->product_name = $request["product_name"];
        $proTrans->reserved = $type === PROTRTYEnum::RESERVA ?  $request["reserved"] : 0;
        $proTrans->stock = $request['stock'];
        $proTrans->sale_id = $sale_id ?: null;
        $proTrans->credit_note_id = $credit_note_id ?: null;
        $proTrans->price = $request['price'];
        $proTrans->min_price = $request['min_price'];
        $proTrans->special_price = $request['special_price'];
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
        $trans->type = PROTRTYEnum::AJUSTE;
        $trans->save();
    }

}
