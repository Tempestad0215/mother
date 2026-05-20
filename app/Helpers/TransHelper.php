<?php

namespace App\Helpers;

use App\Enums\ProductTransactionTypeEnum;
use App\Models\CreditNote;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Sale;
use Illuminate\Http\Request;

class TransHelper
{
    /**
     * @param array $request
     * @param ProductTransactionTypeEnum $type
     * @param Sale $sale
     * @param Product|null $product
     * @param CreditNote|null $credit_note
     * @return void
     */
    public static function store(Array $request, ProductTransactionTypeEnum $type, Sale $sale, ?Product $product = null, ?CreditNote $credit_note = null):void
    {
        ProductTransaction::create(
            [
                'sale_id' => $sale->id,
                'product_uuid' => $product?->uuid,
                'product_name' => $request["product_name"],
                'reserved_quantity' => $type === ProductTransactionTypeEnum::RESERVATION ? $request["stock"] : 0,
                'quantity' => $request['stock'],
                'credit_note_uuid' => $credit_note?->uuid ?: null,
                'price' => $request['price'],
                'min_price' => $request['min_price'],
                'special_price' => $request['special_price'],
                'discount' => $request['discount'],
                'discount_amount' => $request['discount_amount'],
                'tax_rate' => $request['tax_rate'],
                'tax' => $request['tax'],
                'tax_amount' => $request['tax_amount'],
                'amount' => $request['amount'],
                'type' => $type,
            ]
        );
    }


    /**
     * @param Request $request
     * @param ProductTransaction $trans
     * @return void
     */
    public function update(Request $request, ProductTransaction $trans):void
    {
        $trans->product_uuid = $request->get('product_uuid');
        $trans->quantity = $request->get('quantity');
        $trans->price = $request->get('price');
        $trans->sale_id = 0;
        $trans->discount = $request->get('discount');
        $trans->discount_amount = $request->get('discount_amount');
        $trans->tax = $request->get('tax');
        $trans->amount = $request->get('amount');
        $trans->type = ProductTransactionTypeEnum::ADJUSTMENT;
        $trans->save();
    }

}
