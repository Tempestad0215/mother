<?php

namespace App\Http\Resources;

use App\Enums\SaleTypeEnum;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
* @mixin Sale
 */
class SaleInfoResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        // Verificar si existe el clinete
        $existsClient = (bool)$this->client;

        // Tomar los items de la ventas
        $saleItems = SaleItemResource::collection($this->items);


        //Devolver los datos
        return [
            'uuid' => $this->uuid,
            'invoice_type' => $this->invoice_type,
            'ncf' => $this->ncf,
            'ncf_m' => $this->ncf_m,
            'code' => $this->code,
            'client_name' => $this->client_name,
            'client_uuid' => $this->when($existsClient, $this->client?->uuid, null),
            'client_document' => $this->when($existsClient, $this->client?->personal_id, null) ,
            'discount_amount' => $this->discount_amount,
            'tax' => $this->tax,
            'sub_total' => $this->sub_total,
            'amount' => $this->amount,
            'status' => $this->status,
            'type' => $this->type,
            'close_table' => $this->close_table,
            'info_sale' => $this->whenLoaded('items', function() use ($saleItems) {
                return $saleItems;
            }),
            'comment' => $this->comment,
            'created_at' => $this->created_at,
        ];
    }
}
