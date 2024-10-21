<?php

namespace App\Http\Resources;

use App\Enums\SaleTypeEnum;
use App\Models\Comment;
use App\Models\ProTrans;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
 * @property Comment $comment
 */
class SaleInfoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        //Guardar los datos
        $infoFinal = [];

        //Convertir a una collection
        $data = collect($this->infoSale);

        //Recorrer los datos para guardarlos
        $data->map(function (ProTrans $item) use (&$infoFinal) {

            //Agregar los datos
           $infoFinal[] = [
               'id' => $item->id,
               'code' => $item->product->code ?? null,
               'product_id' => $item->product_id,
               'product_name' => $item->product_name,
               'sale_id' => $item->sale_id,
               'amount' => $item->amount,
               'discount' => $item->discount,
               'discount_amount' => $item->discount_amount,
               'price' => $item->price,
               'status' => $item->status,
               'stock' => $item->stock,
               'tax' => $item->tax,
               'tax_rate' => $item->tax_rate,
               'type' => $item->type,
           ];

        });

        return [
            'id' => $this->id,
            'invoice_type' => $this->invoice_type,
            'ncf' => $this->ncf,
            'ncf_m' => $this->ncf_m,
            'code' => $this->code,
            'client_name' => $this->client_name,
            'client_id' => $this->client_id,
            'discount_amount' => $this->discount_amount,
            'tax' => $this->tax,
            'sub_total' => $this->sub_total,
            'amount' => $this->amount,
            'status' => $this->status,
            'type' => $this->type,
            'close_table' => $this->close_table,
            'info_sale' => $infoFinal,
            'comment' => [
                'id' => $this->comment->id,
                'content' => $this->comment->content
            ]
        ];
    }
}
