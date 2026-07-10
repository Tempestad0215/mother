<?php

namespace App\Http\Resources;

use App\Models\PurchaseReceipts;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReceiptItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Obtener los datos antiguo de la recepcion que esa tenga
        $receipts = PurchaseReceipts::with('items')
            ->where('purchase_uuid',$this->purchase_uuid)
            ->get();

        // Total recibido
        $totalYaRecibido = 0;

        foreach ($receipts as $receipt) {
            // tomar los datos por productos
            $itemRecibido = $receipt->items->where('product_uuid', $this->product_uuid)->first();

            // verificar si existe
            if($itemRecibido){
                $totalYaRecibido = bcadd((string)$totalYaRecibido,(string)$itemRecibido->quantity_received,4);
            }
        }

        // Retornar la canatidad esperada
        $cantidadPendiente = bcsub((string)$this->quantity, (string)$totalYaRecibido,4);

        // verificar la cantidad pendiene
        if($cantidadPendiente < 0){
            $cantidadPendiente = 0;
        }

        // Informacion de los impuestos

        $itbisPorcentaje = bcdiv($this->taxR->rate,"100",2);

        //
        $itbisRate = $this->taxR->rate;
        $tax_amount = bcmul($itbisPorcentaje, $this->cost);
        // Nuevo itbis
        $taxNew = bcmul($tax_amount, $cantidadPendiente,4);
        // Sub Total
        $subTotal = bcmul($cantidadPendiente, $this->cost,4);
        // Cantidad total
        $amount = bcadd($subTotal, $taxNew,2);



        // Devolver los dato ya listo
        return [
            ...parent::toArray($request),
            'quantity' => $cantidadPendiente,
            'tax' => $taxNew,
            'amount' => $amount,
            'product_name' => $this->product?->name,
            'tax_rate' => $this->taxR?->rate ?? $this->product->tax_rate ?? 0,
            'warehouse_name' => $this->warehouse?->name,
            'tax_amount' => (float)$taxNew
        ];
    }
}
