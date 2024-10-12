<?php

namespace App\Helpers;

use App\Enums\ProductTransType;
use App\Enums\ProductTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Enums\SequenceTypeEnum;
use App\Http\Requests\StoreProductSaleRequest;
use App\Models\CreditNote;
use App\Models\Product;
use App\Models\ProTrans;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreditNoteHelper
{

    /**
     * @param StoreProductSaleRequest $request
     * @param Sale $sale
     * @return void
     */
    public function creditNoteStore(StoreProductSaleRequest $request, Sale $sale):void
    {
        //Asegurar que los procesos se cumplan
        DB::transaction(function () use ($request, $sale) {

            //Convertir a collection
            $infoCollect = collect($request->get('info_sale'));
            $saleCollect = collect($sale->infoSale);


            //Obtener el tipo de devolucion
            $type = $request->get('type');

            //Verificar si existe para aumentar el contador de la nota de credito
            if ($type == SaleTypeEnum::DEVOLUCION->value)
            {
                //Crear el aumento de los comprobante
                SequenceHelper::incrementSequence(SequenceTypeEnum::B04);
            }

            //Crear la devolucion
            $creditNote = CreditNote::create([
                'sale_id' => $sale->id,
                'client_id' => $request->get('client_id') ?: null,
                'client_name' => $request->get('client_name'),
                'client_rnc' => $request->get('client_rnc'),
                'ncf' => $request->get('ncf'),
                'ncf_m' => $request->get('ncf_m'),
                'discount_amount' => $request->get('discount_amount'),
                'discount' => $request->get('discount'),
                'tax' => $request->get('tax'),
                'sub_total' => $request->get('sub_total'),
                'amount' => $request->get('amount'),
                'type' => SaleTypeEnum::DEVOLUCION,
                'n_available' => $request->get('amount'),
            ]);


            //Crear el comentario de la devolucion
            $creditNote->comment()->create(
                ['content' => $request->get('comment')]
            );

            //sumatoria para ver si se cerro la cuenta
            $resultTotal = [];


            //Recorrer los datos
            $infoCollect->map(callback: function ($item) use (&$saleCollect, &$sale, &$creditNote, &$resultTotal) {

                //buscar los productos
                $product = Product::find($item['product_id']);

                //Buscar la concidencia en los datos antiguo
                $saleInfo = $saleCollect->firstWhere('product_id', $item['product_id']);

                //sacar el resultado
                $result  =  $saleInfo['stock'] - $item['stock'];

                //Si el producto es de servicio el resultado debe ser 0
                if ($product->type == ProductTypeEnum::SERVICIO && $result != 0)
                {
                    // Devolver error si no coincide
                    throw ValidationException::withMessages([
                        'general' => "Por Favor, No Puede Modificar La Cantidad Del Item: $product->name"
                    ]);

                }else if ($result < 0)
                {
                    // Devolver error si no coincide
                    throw  ValidationException::withMessages([
                        'general' => "Por Favor, El Item: $product->name, La Cantidad Es Mayor Que La Factura"
                    ]);
                }
                else{

                    //Crear la transaccion individual
                    ProTrans::create([
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'sale_id' => $sale->id,
                        'credit_note_id' => $creditNote->id,
                        'stock' => $item['stock'],
                        'price' => $item['price'],
                        'tax_rate' => $item['tax_rate'],
                        'tax' => $item['tax'],
                        'amount' => $item['amount'],
                        'discount' => $item['discount'],
                        'discount_amount' => $item['discount_amount'],
                        'type' => ProductTransType::DEVOLUCION,
                        'status' => false
                    ]);

                    //Verificar que el producto sea el mismo que el de la transation
                    $productCheck = $product->trans->where('id', $item['id'])->first();


                    //actializar los datos del stock
                    $product->increment('stock', $item['stock']);
                    //Verificar si es resera o no
                    if ($productCheck->type == ProductTransType::RESERVA)
                    {
                        //Deducir de la reserva
                        $product->decrement('reserved', $item['stock']);
                    }

                    //Tomar el resultado
                    $result = $saleInfo->stock - $item['stock'];

                    //Agreagr a resultado final
                    $resultTotal[] = $result;


                    //Si el resultado es cero, pues se
                    if ($result == 0)
                    {
                        // Actualizar el status del producto
                        ProTrans::where('id', $saleInfo->id)
                            ->update([
                                'status' => false
                            ]);
                    }

                }
            });

            //Tomar el sale id para actualizar datos
            $saleId = $saleCollect[0]->sale_id;

            //Verificar si es menor o igual a 0
            if (array_sum($resultTotal) <= 0)
            {

                Sale::where('id', $saleId)->update([
                    'close_table' => true
                ]);
            }

            //DEvolver exito
            return redirect()->route('sale.create');
        });

    }


}
