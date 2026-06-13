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
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
             if ($data->type == SaleTypeEnum::Devolucion->value) {
                 //Crear el aumento de los comprobante
                 SequenceHelper::incrementSequence(SequenceSaleTypeEnum::B04);
             }
             // Limpiar los datos para crear la nota de credito
             $cleanData = collect($data->toArray())->except(['uuid', 'status'])->toArray();
             // Colocar el n_available igual al monto de la nota de credito
             $cleanData['n_available'] = $data->amount;

             // Verificar si existe otra nota de credito relacionada con la venta
             $existingCreditNotes = CreditNote::where('sale_uuid', $data->uuid)->where('status', true)->get();

             // Iniciar el item en un collect vacio
             $groupedItems = collect();

             // Recorrer las notas de credito existentes para verificar los productos
             $allItems = $existingCreditNotes->flatMap(function ($creditNote) {
                 return $creditNote->items;
             });

             //Crear la devolucion
             $creditNote = CreditNote::create([
                 ...$cleanData,
                 'sale_uuid' => $data->uuid
             ]);

             // Verificar si hay items en las notas de credito existentes
             if ($allItems->isNotEmpty()) {
                 // // Agrupar por producto y sumar las cantidades
                 /** @var Collection<string, float> $groupedItems */
                 $groupedItems = $allItems->groupBy('product_uuid')->map(function (Collection $item) {
                     return $item->sum('quantity');
                 });
             }

             // Obtener todos los item de la ventas, para ver las cantidades
             $saleItems = $sale->items->keyBy('product_uuid');


             // Obtener todos los id de productos
             $productUuids = array_map(fn(SaleItemDto $item) => $item->product_uuid, $data->info_sale);

             // Obtenir los productos por uuid
             $productsDB = Product::whereIn('uuid', $productUuids)->get()->keyBy('uuid');

             $creditNoteItemsSave = [];
             $stockMovement = [];
             $productMovements = [];


             //Recorrer los datos
             collect($data->info_sale)->each(function (SaleItemDto $item) use ($groupedItems, $saleItems, &$creditNoteItemsSave, $creditNote, &$stockMovement, $productsDB, &$productMovements) {
                 // Obtener el valor del item y la cantidad sumana
                 $oldCurrentQuantity = 0.0;

                 // Verificar si existe alguna devolucion
                 if ($groupedItems->isNotEmpty()) {
                     $oldCurrentQuantity = $groupedItems->get($item->product_uuid) ?? 0;
                 }

                 // Tomar la cantidad de la venta
                 $saleQuantity = $saleItems->get($item->product_uuid)->stock ?? 0;

                 // Calcular la diferencia
                 $newQuantity = bcadd($item->stock, $oldCurrentQuantity, 4);

                 // Verificar si la cantidad es mayor a la cantidad de venta
                 if ($newQuantity > $saleQuantity) {
                     // Devolver error si no coincide
                     throw ValidationException::WithMessages([
                         'info_sale' => "La cantidad de $item->product_name no puede ser mayor a la cantidad de Venta"
                     ]);
                 }

                 /** @var Product $productModel */
                 $productModel = $productsDB->get($item->product_uuid);
                 $warehousePivot = $productModel?->warehouses->firstWhere('uuid', $item->warehouse_uuid);

                 // Tomar el stock actual
                 $currentStock = $warehousePivot?->pivot->available ?? 0.0;

                 // Sumar la cantidad
                 $finalWarehouseStock = bcadd((string)$currentStock, (string)$item->stock, 4);

                // Guardar el movimiento de producto
                 $productMovements[] = [
                     'uuid' => Str::uuid(),
                     'product_uuid' => $item->product_uuid,
                     'warehouse_uuid' => $item->warehouse_uuid,
                     'type' => "IN",
                     'concept' => "Devolucion en nota de Credito de Producto : $item->product_name en el Almacen: $warehousePivot->name",
                     'quantity' => $item->stock,
                     'cost' => $item->price,
                     'stock_before' => $currentStock,
                     'stock_after' => $finalWarehouseStock,
                     'created_at' => now(),
                     'updated_at' => now(),
                 ];

                 // Guardar el movimiento de stock
                 $stockMovement[] = [
                     'product_uuid' => $item->product_uuid,
                     'warehouse_uuid' => $item->warehouse_uuid,
                     'stock_quantity' => (float)$finalWarehouseStock,
                     'updated_at' => now(),
                 ];

                 // Crear el item de la nota de credito
                 $creditNoteItemsSave[] = new CreditNoteItem([
                     'credit_note_uuid' => $creditNote->uuid,
                     'product_uuid' => $item->product_uuid,
                     'quantity' => $item->stock,
                     'price' => $item->price,
                     'tax' => $item->getTax(),
                     'sub_total' => $item->amount,
                     'amount' => $item->getAmount(),
                 ]);

             });

             if (!empty($stockMovement)) {
                 DB::table('warehouse_products')->upsert(
                     $stockMovement,
                     ['product_uuid', 'warehouse_uuid'],
                     ['stock_quantity', 'updated_at'],
                 );
             }

             // Guardar los items en la nota de credito
             $creditNote->items()->saveMany($creditNoteItemsSave);

             // Actualizar el stock de los productos
             InventoryMovement::insert(
                 $productMovements,
             );

             return $creditNote;
         });

    }


    /**
     * @param string $code
     * @return JsonResponse|null
     */
    public static function creditNoteGet(string $code): ?JsonResponse
    {
        // Buscar la nota de credito por codigo o ncf
        $creditNote = CreditNote::where(function (Builder $q) use ($code){
            $q->where('code', $code)
                ->orWhere('ncf',$code);
        })->where('n_available','>',0)
            ->where('created_at','>=',  now()->subDays(15))
            ->select(['uuid','ncf','n_available','code', 'created_at'])
            ->first();

        // Verificar si existe
        if(!$creditNote)
        {
            return null;
        }

        // Calcular el tiempo restante para expirar
        $dayRemaining = 15 - now()->diffInDays($creditNote->created_at);

        // Devolver la respuesta
        return response()->json([
           'data' => $creditNote,
           'dayRemaining' => $dayRemaining,
           'expireSoon' => $dayRemaining <= 5,
        ]);

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
