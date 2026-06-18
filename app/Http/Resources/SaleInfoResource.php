<?php

namespace App\Http\Resources;

use App\Enums\SaleTypeEnum;
use App\Models\Client;
use App\Models\Comment;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property string $uuid
 * @property string $invoice_type
 * @property string $ncf
 * @property string $ncf_m
 * @property string $code
 * @property Client $client
 * @property string $client_name
 * @property string $client_uuid
 * @property float $discount
 * @property float $discount_amount
 * @property float $tax
 * @property float $sub_total
 * @property float $amount
 * @property boolean $status
 * @property SaleTypeEnum $type
 * @property bool $close_table
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property SaleItem[] $items
 * @property Comment $comment
 */
class SaleInfoResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $existsClient = (bool)$this->client;


        //DEvolver los datos
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
            'info_sale' => $this->whenLoaded('items', function() {
                return SaleItemResource::collection($this->items);
            }),
            'comment' => $this->comment,
            'created_at' => $this->created_at,
        ];
    }
}
