<?php

namespace App\Helpers;

use App\Dtos\SaleCreditNoteDto;
use App\Dtos\SaleDto;
use App\Dtos\SaleItemDto;
use App\Enums\ProductTransactionTypeEnum;
use App\Enums\ProductTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Enums\SequenceSaleTypeEnum;
use App\Http\Requests\StoreProductSaleRequest;
use App\Models\CreditNote;
use App\Models\CreditNoteItem;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use JetBrains\PhpStorm\NoReturn;
use Throwable;

class CreditNoteHelper
{

    /**
     * @param StoreProductSaleRequest $request
     * @param Sale $sale
     * @return CreditNote
     * @throws Throwable
     */
    public function creditNoteStore(StoreProductSaleRequest $request, Sale $sale): CreditNote
    {
        //Asegurar que los procesos se cumplan
         return DB::transaction(function () use ($request, $sale) {

            //Convertir a collection
            $data = SaleDto::fromArray($request->validated());
            $infoCollect = collect($request->input('info_sale'));
            $saleCollect = collect($sale->infoSale);

            //Verificar si existe para aumentar el contador de la nota de credito
            if ($data->type == SaleTypeEnum::Devolucion->value)
            {
                //Crear el aumento de los comprobante
                SequenceHelper::incrementSequence(SequenceSaleTypeEnum::B04);
            }
        
            // Limpiar los datos para crear la nota de credito
            $cleanData = collect($data->toArray())->except(['uuid','status'])->toArray();

            // Colocar el n_available igual al monto de la nota de credito
            $cleanData['n_available'] = $data->amount;


            //Crear la devolucion
            $creditNote = CreditNote::create([
                ...$cleanData,
                'sale_id' => $data->uuid
            ]);

            //sumatoria para ver si se cerro la cuenta
            $resultTotal = [];

            // Verificar si existe otra nota de credito relacionada con la venta
            $existingCreditNotes = CreditNote::where('sale_uuid', $data->uuid)->where('status', true)->get();

            $groupedItems = [];

            // Recorrer las notas de credito existentes para verificar los productos
            /** @var Collection<int, CreditNoteItem> */
           $allItems = $existingCreditNotes->flatMap(function( $creditNote) {
                return $creditNote->items;
           });

           // Verificar si hay items en las notas de credito existentes
           if(count($allItems) > 0){
                // // Agrupar por producto y sumar las cantidades
                $groupedItems = $allItems->groupBy('product_uuid')->map(function($item) {
                    return $item->sum('quantity');
                });
           }

           
        
            //Recorrer los datos
            /** @var SaleItemDto $value */
            foreach ($data->info_sale as $value) {
           
                // Buscar el total de la cantidad del producto en las notas de credito existentes
                $oldQuantityTotal = $groupedItems->get($value->product_uuid) ?? 0;
                
                
                $newQuantity = bcadd($oldQuantityTotal, $value->quantity);

                dd($newQuantity);
    
                // Buscar el producto para verificar el tipo
                $currentProduct = $sale->items->keyBy('product_uuid');

                // Actual valor 
                $currentValue = $currentProduct->get($value->product_uuid); 

                dd($currentValue);


            }
            $infoCollect->map(callback: function ($item) use (&$saleCollect, &$sale, &$creditNote, &$resultTotal) {

                //buscar los productos
                $product = Product::find($item['product_id']);

                //Buscar la concidencia en los datos antiguo
                $saleInfo = $saleCollect->firstWhere('product_id', $item['product_id']);

                //sacar el resultado
                $result  =  $saleInfo['stock'] - $item['stock'];


                //Si el producto es de servicio el resultado debe ser 0
//                if ($product->type == ProductTypeEnum::SERVICIO && $result != 0)
//                {
//                    // Devolver error si no coincide
//                    throw ValidationException::withMessages([
//                        'general' => "Por Favor, No Puede Modificar La Cantidad Del Item: $product->name"
//                    ]);
//
//                }else
                if ($result < 0)
                {
                    // Devolver error si no coincide
                    throw  ValidationException::withMessages([
                        'general' => "Por Favor, El Item: $product->name, La Cantidad Es Mayor Que La Factura"
                    ]);
                }
                else{

                    //Crear la transaccion individual
                    TransHelper::store($item, ProductTransactionTypeEnum::RETURN, $sale,$product, $creditNote);

                    // Verificar si la nota de credito y la venta es 0
                    $amount = $sale->amount - $creditNote->amount;

                    //si el salgo es igual a 0 se coloca la venta en status 0
                    if ($amount == 0)
                    {
                        $sale->update([
                            'status' => false
                        ]);
                    }
                    //Verificar que el producto sea el mismo que el de la transation
                    $productCheck = $product->trans->where('id', $item['id'])->first();

                    // Verificar si es productos para actualizar el inventario
                    if ($product->type === ProductTypeEnum::Producto)
                    {
                        //actializar los datos del stock
                        $product->increment('stock', $item['stock']);

                        //Verificar si es resera o no
                        if ($productCheck?->type == ProductTransactionTypeEnum::RESERVATION)
                        {
                            //Deducir de la reserva
                            $product->decrement('reserved', $item['stock']);
                        }
                    }


                    //Tomar el total de toda la devoluciones
                    $stockRet = $product->trans
                        ->where('type', ProductTransactionTypeEnum::RETURN)
                        ->where('sale_id', $sale->id)
                        ->sum('quantity');


                    //Tomar el resultado
                    $result = $saleInfo->stock - $stockRet;

                    //Agreagr a resultado final
                    $resultTotal[] = $result;

                    //Si el resultado es cero, pues se
                    if ($result == 0.0)
                    {

                        // Actualizar el status del producto
                        ProductTransaction::where('uuid', $saleInfo->uuid)
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

            //Devolver el dato para el json
            return $creditNote;
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


    /**
     * Verificar la notas de credito
     * @param SaleCreditNoteDto[] $info
     * @param float $amount
     * @return void
     */
    public static function updateAvailableFor(array $info = [], float $amount = 0):void
    {
        if (empty($info) || $amount <= 0) {
            return;
        }

        //Total de nota de credito
        $totalCredit = array_sum(
            Arr::map(
                $info,
                fn(SaleCreditNoteDto $dto) => $dto->n_available
            ));
        //Scar el resultado de la nota de credito y la venta total
        $result =  $totalCredit - $amount;

        //Verificar si es mayor a cero
        if ($result < 0)
        {
            //Recorrar los datos para actualizar
            foreach ($info as $item)
            {
                //colocar en 0 las notas de credito
                CreditNote::where('uuid', $item['uuid'])
                    ->update([
                       'n_available' => 0,
                        'status' => false
                    ]);
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

                //Evitar que esta se actualize
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



    //Buscar el balance de la nota de credito
    #[NoReturn]
    public static function getBalance (string $code):void
    {

        $creditNote = self::creditNoteGet($code);


        dd($creditNote);


    }



}
