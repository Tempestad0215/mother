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
use Illuminate\Database\Eloquent\Builder;
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

                    //Tomar el total de toda la devoluciones
                    $stockRet = $product->trans
                        ->where('type', ProductTransType::DEVOLUCION)
                        ->where('sale_id', $sale->id)
                        ->sum('stock');

                    //Tomar el resultado
                    $result = $saleInfo->stock - $stockRet;

                    //Agreagr a resultado final
                    $resultTotal[] = $result;

                    //Si el resultado es cero, pues se
                    if ($result == 0.0)
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


    /**
     * @param string $code
     * @return CreditNote|null
     */
    public static function creditNoteGet(string $code):CreditNote|null
    {
        return CreditNote::where(function (Builder $q) use ($code){
            $q->where('code', $code)
                ->orWhere('ncf',$code);
        })->where('n_available','>',0)
            ->select(['id','ncf','n_available','code'])
            ->first() ?? null;

    }


    public static function updateAvailableFor(array $info, float $amount)
    {
        //Total de nota de credito
        $totalCredit = array_sum(array_column($info, 'n_available'));
        //Scar el resultado de la nota de credito y la venta total
        $result =  $totalCredit - $amount;

        //Verificar si es mayor a cero
        if ($result < 0)
        {
            //Recorrar los datos para actualizar
            foreach ($info as $item)
            {
                //colocar en 0 las notas de credito
                CreditNote::where('id', $item['id'])
                    ->update([
                       'n_available' => 0,
                        'status' => false
                    ]);

                dd($item['id']);
            }

        //Si el balance de la nota de credito es mayor
        }else{

            //Convertir a collecion
            $infoCollect = collect($info);
            //Buscar la que tenga el balance mas alto
            $maxData = $infoCollect->sortByDesc('n_available')->first();



            $infoCollect->map(function ($item) use ($maxData, $result){
                //tomar los datos de la nota de credito maxima
                $maxId = $maxData['id'];
                $maxAve = $maxData['n_available'];


                //Buscar la nota de credito
                $credit = CreditNote::find($item['id']);

                //Evitar que esta se actualize del todo
                if ($item['n_available'] == $maxAve && $maxId == $item['id'])
                {

                    //Reducir el resultado el cual es positivo
                    $credit->update([
                        'n_available' => $result
                    ]);

                }else{

                    //Ponerla en 0 y Quitarle el status
                    $credit->update([
                        'n_available' => 0,
                        'status' => false
                    ]);
                }
            });

        }
    }

}
