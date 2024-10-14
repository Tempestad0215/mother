<?php

namespace App\Http\Resources;

use App\Enums\ProductTransType;
use App\Enums\SaleTypeEnum;
use App\Models\Comment;
use App\Models\CreditNote;
use App\Models\Product;
use App\Models\ProTrans;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

/**
 * @property int $id
 * @property string $invoice_type
 * @property string $ncf
 * @property string $ncf_m
 * @property string $code
 * @property string $client_name
 * @property int $client_id
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
 * @property ProTrans[] $infoSale
 * @property ProTrans[] $sale
 * @property ProTrans[] $trans
 * @property Comment $comment
 */

class SaleCreditNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        //Convertir al collectio
        $infoCollect = collect($this->infoSale);

        //Para pasar los datos
        $info = [];

        //Recorrer los datos
        $infoCollect->map(function (ProTrans $item) use (&$info) {

            //Obtener los productos que tengan devolucion pendiente
            $transProduct = ProTrans::where('product_id', $item->product_id)
                ->where('sale_id', $item->sale_id)
                ->whereIn('type', [ProductTransType::VENTAS, ProductTransType::RESERVA])
                ->where('status', true)
                ->first();


            //Conseguir la transacciones con devolucion existente
            $transRet = ProTrans::where('product_id', $item->product_id)
                ->where('sale_id', $item->sale_id)
                ->where('type', [ProductTransType::DEVOLUCION])
                ->where('status', false)
                ->sum('stock');

            //Verificar si existe el productos
            $existsProduct = isset($transProduct);

            //Tomar los resultado de stock
            $productStock = $existsProduct ? $transProduct->stock : 0;

            //Tomar el resultado
            $result =  $productStock - $transRet;

            //Si el item tiene disponible
            if ($existsProduct && $item->type != ProductTransType::DEVOLUCION && $result != 0)
            {
                //Crear la informacion
                $info[] = [
                    "id" => $item['id'],
                    "code" => $transProduct->product->code,
                    "sale_id" => $item['sale_id'],
                    "product_id" => $transProduct->product->id,
                    "credit_note_id" => null,
                    "product_name" => $transProduct->product->name,
                    "stock" => $result == 0 ? $item['stock'] : abs($result),
                    "price" => $item['price'],
                    "tax_rate" => $item['tax_rate'],
                    "tax" => $item['tax'],
                    "amount" => $item['amount'],
                    "discount" => $item['discount'],
                    "discount_amount" => $item['discount_amount'],
                    "type" => SaleTypeEnum::VENTAS->value,
                    "status" => $item['status']
                ];
            }
        });


        //verificar si esta vacio la info
        if (count($info) == 0)
        {
            throw ValidationException::withMessages([
                'general' => "Este Documento No Tiene Item Disponible Para NC"
            ]);
        }



        //Devolver los datos formateado
        return [
            "id" => $this->id,
            "invoice_type" => $this->invoice_type,
            "ncf" => $this->ncf,
            "ncf_m" => $this->ncf_m,
            "code" => $this->code,
            "client_name" => $this->client_name,
            "client_id" => $this->client_id,
            "discount_amount" => $this->discount_amount,
            "tax" => $this->tax,
            "sub_total" => $this->sub_total,
            "amount" => $this->amount,
            "status" => $this->status,
            "type" => $this->type,
            "close_table" => $this->close_table,
            "info_sale" => $info,
            "comment" => [
                "id" => $this->comment->id,
                "content" => $this->comment->content,
            ],
        ];
    }
}
