<?php

namespace App\Helpers;

use App\Enums\TransTypeEnum;
use App\Models\CreditNote;
use App\Models\Product;
use App\Models\ProTrans;
use App\Models\Sale;
use Illuminate\Http\Request;

class TransHelper
{
    /**
     * @param array $request
     * @param TransTypeEnum $type
     * @param Sale $sale
     * @param Product|null $product
     * @param CreditNote|null $credit_note
     * @return void
     */
    public static function store(Array $request, TransTypeEnum $type, Sale $sale, Product $product = null, CreditNote $credit_note = null):void
    {


        //Crear o actualizar si no existe
        ProTrans::create(
            [
                'sale_id' => $sale->id,
                'product_id' => $product?->id,
                'product_name' => $request["product_name"],
                'reserved' => $type === TransTypeEnum::RESERVA ? $request["stock"] : 0,
                'stock' => $request['stock'],
                'credit_note_id' => $credit_note?->id ?: null,
                'price' => $request['price'],
                'min_price' => $request['min_price'],
                'special_price' => $request['special_price'],
                'discount' => $request['discount'],
                'discount_amount' => $request['discount_amount'],
                'tax_rate' => $request['tax_rate'],
                'tax' => $request['tax'],
                'amount' => $request['amount'],
                'type' => $type,
            ]
        );
            // Esto fue refactorizadoel 23-02-25
//        //Crear el TransId
//        $transId = $request['transID'] ?? null;
//
//        //Verificar si existe la transcciones antiguia
//        $transOld = ProTrans::find($transId);
//
//        //Verificar si existe o no
//        $proTrans = $transOld && $type == TransTypeEnum::RESERVA ? $transOld : new ProTrans();
//
//
//        throw new \Exception(json_encode($transOld));
//
//        //Crear la transacion
//        $proTrans->product_id = $product_id ?: $request['product_id'];
//        $proTrans->product_name = $request["product_name"];
//        $proTrans->reserved = $type === TransTypeEnum::RESERVA ?  $request["reserved"] : 0;
//        $proTrans->stock = $request['stock'];
//        $proTrans->sale_id = $sale_id ?: null;
//        $proTrans->credit_note_id = $credit_note_id ?: null;
//        $proTrans->price = $request['price'];
//        $proTrans->min_price = $request['min_price'];
//        $proTrans->special_price = $request['special_price'];
//        $proTrans->discount = $request['discount'];
//        $proTrans->discount_amount = $request['discount_amount'];
//        $proTrans->tax_rate = $request['tax_rate'];
//        $proTrans->tax = $request['tax'];
//        $proTrans->amount = $request['amount'];
//        $proTrans->type = $type;
//        $proTrans->save();

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
        $trans->type = TransTypeEnum::AJUSTE;
        $trans->save();
    }

}
