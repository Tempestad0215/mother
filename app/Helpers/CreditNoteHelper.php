<?php

namespace App\Helpers;

use App\Dtos\CreditNoteInfoSale;
use App\Dtos\SaleCreditNoteDto;
use App\Dtos\SaleDto;
use App\Dtos\SaleItemDto;
use App\Enums\SaleTypeEnum;
use App\Enums\SequenceSaleTypeEnum;
use App\Http\Requests\StoreProductSaleRequest;
use App\Models\CreditNote;
use App\Models\CreditNoteItem;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use JetBrains\PhpStorm\NoReturn;
use Laravel\Octane\Exceptions\DdException;
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
        $creditNote = CreditNote::where(function (Builder $q) use ($code) {
            $q->where('code', $code)
                ->orWhere('ncf', $code);
        })->where('n_available', '>', 0)
            ->where('created_at', '>=', now()->subDays(15))
            ->select(['uuid', 'ncf', 'n_available', 'code', 'created_at'])
            ->first();

        // Verificar si existe
        if (!$creditNote) {
            return null;
        }

        // Calcular el tiempo restante para expirar
        $dayRemaining = 15 - now()->diffInDays($creditNote->created_at);

        // Devolver la respuesta
        return response()->json([
            'uuid' => $creditNote->uuid,
            'ncf' => $creditNote->ncf,
            'n_available' => $creditNote->n_available,
            'n_available_new' => 0,
            'code' => $creditNote->code,
            'created_at' => $creditNote->created_at,
            'dayRemaining' => $dayRemaining,
            'expireSoon' => $dayRemaining <= 5,

        ]);

    }


    /**
     * Verificar la notas de credito
     * @param CreditNoteInfoSale[] $info
     * @param Sale $sale
     * @return void
     */
    public static function updateAvailableFor(array $info, Sale $sale): void
    {
        // Verificar si hay datos
        if (empty($info)) {
            return;
        }


        // Obtener los uuids de las notas de credito
        $uuids = collect($info)->pluck('uuid')->toArray();

        // Obtener las notas de credito por uuid
        $creditNoteFromDB = CreditNote::whereIn('uuid', $uuids)
            ->where('status', true)
            ->where('n_available', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get()
            ->keyBy('uuid');

        // Varlidar si existen todas la notas de creditos
        foreach ($info as $item) {
            if (!$creditNoteFromDB->has($item->uuid)) {
                throw ValidationException::withMessages([
                    'credit_note' => "Nota de crédito {$item->code} no está disponible"
                ]);
            }
        }

        // Ordernar las notas de creditos
        /** @var Collection<string, CreditNote> $sortedCreditNotes */
        $sortedCreditNotes = $creditNoteFromDB->sortBy('created_at');

        // Tomar los que queda
        $remainingAmount = $sale->amount;
        $notedToUpdated = [];
        $creditNoteSale = [];

        // Recorrer las notas de creditos
        foreach ($sortedCreditNotes as $creditNote) {
            // Verificar si el monto es mayor a 0c
            if ($remainingAmount <= 0) {
                break;
            }

            // Tomar el monto de la nota de credito
            $availableAmount = (float)$creditNote->n_available;

            $appliedAmount = min($remainingAmount, $availableAmount);

            // Calcular el nuevo monto disponible
            $newAvailable = bcsub((string)$availableAmount, (string)$remainingAmount, 4);

            // Verificar si el monto es mayor al monto restante
            if ($availableAmount >= $remainingAmount) {
                // Actualizar el monto de la nota de credito

                // Actualizar el array
                $notedToUpdated[$creditNote->uuid] = [
                    'n_available' => $newAvailable,
                    'status' => bccomp($newAvailable, '0', 4) > 0,
                ];
                // Actualizar el monto restante
                $remainingAmount = 0;
            } else {
                // Actualizar el monto de la nota de credito
                $notedToUpdated[$creditNote->uuid] = [
                    'n_available' => '0',
                    'status' => false
                ];
                // Actualizar el monto restante
                $remainingAmount = bcsub((string)$remainingAmount, (string)$availableAmount, 4);
            }

            $creditNoteSale[] = [
                'sale_uuid' => $sale->uuid,
                'credit_note_uuid' => $creditNote->uuid,
                'applied_amount' => $appliedAmount, // ← Aquí va el monto aplicado, no el newAvailable
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }


        // Actualizar los datos de la nota de credito
        foreach ($notedToUpdated as $uuid => $data) {
            CreditNote::where('uuid', $uuid)->update($data);
        }

        if(!empty($creditNoteSale))
        {
            DB::table('credit_note_sale')->insert($creditNoteSale);
        }


    }


    //Buscar el balance de la nota de credito
    #[NoReturn]
    public static function getBalance(string $code): void
    {

        $creditNote = self::creditNoteGet($code);


    }


}
