<?php

namespace App\Http\Resources;

use App\Enums\ClientDocumentEnum;
use App\Enums\ClientTypeEnum;
use App\Enums\ClientTypePriceEnum;
use App\Models\Account;
use App\Models\Client;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;


/**
 * @mixin Client
 */
class ClientCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * Devolver los datos
         */
        return [
            'uuid' => $this->uuid,
            'code' => $this->code,
            'name' => $this->name,
            'personal_id' => $this->personal_id,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'document' => $this->document,
            'type' => $this->type,
            'type_price' => $this->type_price,
            'status' => $this->status,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }


}
