<?php

namespace App\Http\Resources;

use App\Dtos\SaleItemDto;
use App\Enums\SaleTypeEnum;
use App\Models\Comment;
use App\Models\CreditNote;
use App\Models\ProductTransaction;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

/**
 * @property string $id
 * @property string $invoice_type
 * @property string $ncf
 * @property string $ncf_m
 * @property string $client_name
 * @property string $client_uuid
 * @property float $discount_amount
 * @property float $tax
 * @property float $sub_total
 * @property float $amount
 * @property boolean $status
 * @property SaleTypeEnum $type
 * @property bool $close_table
 * @property Carbon $created_at,
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property SaleItem[] $items
 * @property ProductTransaction[] $sale
 * @property ProductTransaction[] $trans
 * @property Collection<int, CreditNote> $credit_note
 * @property Comment $comment
 */

class SaleCreditNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        /** @var Collection<int, CreditNote> $creditNotes */
        $creditNotes = $this->whenLoaded('credit_note', $this->credit_note);

        // Crear un arreglo para almacenar los items disponibles
        $availableItems = collect();

        foreach ($this->items as $item){

            /** @var float $totalReturned */
            $totalReturned = $creditNotes->flatMap(fn(CreditNote $cr) => $cr->items)
                ->where('product_uuid', $item->product_uuid)
                ->sum('quantity');

            // Restar las devoluciones con el stock a realizado
            $availableStock = bcsub((string)$item->stock, (string)$totalReturned);

            // Vericiar si el estock es mayor a cero
            if($availableStock > 0) {
                // Tomar el array para asingar nuevos datos
                $saleItemArray = $item->toArray();
                $saleItemArray['product_name'] = $item->product->name;
                $saleItemArray['tax_uuid'] = $item->product->tax_uuid;
                $saleItemArray['warehouse_uuid'] = $item->product->default_warehouse;
                $saleItemArray['stock'] = $availableStock;

                // Agregar el item al arreglo
                $availableItems->push(
                    SaleItemDto::fromArray($saleItemArray)
                );
            }
        }

        // Verificar si hay items disponibles
        if(count($availableItems) <= 0)
        {
            // Lanzar una excepcion
            throw ValidationException::withMessages([
                'general' => "Este Documento No Tiene Item Disponible Para NC"
            ])->status(409);
        }

        // Devolver los datos
        return [
            ...parent::toArray($request),
            'info_sale' => $availableItems,
        ];

    }
}
