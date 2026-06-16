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

            //Verificar si existe para aumentar el contador de la nota de credito
            if ($data->type == SaleTypeEnum::Devolucion->value) {
                //Crear el aumento el comprobante
                SequenceHelper::incrementSequence(SequenceSaleTypeEnum::B04);
            }
            // Limpiar los datos para crear la nota de credito
            $cleanData = collect($data->toArray())->except(['uuid', 'status'])->toArray();
            // Colocar el n_available igual al monto de la nota de credito
            $cleanData['n_available'] = $data->amount;

            //Crear la devolucion
            $creditNote = CreditNote::create([
                ...$cleanData,
                'sale_uuid' => $data->uuid
            ]);

            // Los datos para insertar
            $creditNoteItemsSave = [];

            //Recorrer los datos
            collect($data->info_sale)->each(function (SaleItemDto $item) use (&$creditNoteItemsSave, $creditNote) {
                // Crear el item de la nota de credito
                $creditNoteItemsSave[] = new CreditNoteItem([
                    'credit_note_uuid' => $creditNote->uuid,
                    'product_uuid' => $item->product_uuid,
                    'warehouse_uuid' => $item->warehouse_uuid,
                    'quantity' => $item->stock,
                    'price' => $item->price,
                    'tax' => $item->getTax(),
                    'sub_total' => $item->amount,
                    'amount' => $item->getAmount(),
                ]);

            });

            // Guardar los items en la nota de credito
            $creditNote->items()->saveMany($creditNoteItemsSave);


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
