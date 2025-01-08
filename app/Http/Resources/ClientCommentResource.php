<?php

namespace App\Http\Resources;

use App\Enums\CLDOCENUM;
use App\Enums\CLTYEnum;
use App\Enums\ClientTypePriceEnum;
use App\Models\Account;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;


/**
 * @property string uuid;
 * @property string name
 * @property string phone
 * @property string personal_id
 * @property string email
 * @property CLDOCENUM document
 * @property string address
 * @property boolean status
 * @property float limit
 * @property integer due_date
 * @property CLTYEnum type
 * @property float late_fee_interest
 * @property float balance
 * @property float consumed
 * @property ClientTypePriceEnum type_price
 * @property boolean receive_email
 * @property Account account
 * @property Comment comment
 * @property Date deleted_at
 * @property Date created_at
 * @property Date updated_at
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
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'personal_id' => $this->personal_id,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'document' => $this->document,
            'type' => $this->type,
            'type_price' => $this->type_price,
            'status' => $this->status,
            'comment' => [
                'uuid' => $this->comment?->uuid,
                'content' => $this->comment?->content,
                'created_at' => $this->comment?->created_at,
            ],
            'amount' => $this->account?->amount,
            'due_date' => $this->account?->due_date,
            'late_fee' => $this->account?->late_fee,
            'balance' => $this->account?->balance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }


}
