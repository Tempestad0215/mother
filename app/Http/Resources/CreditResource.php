<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @property numeric $balance
 * @property numeric $consumed
 * @property numeric $due_date
 * @property numeric $late_fee_interest
 * @property numeric $limit
 *
 */

class CreditResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'balance' => $this->balance,
            'consumed' => $this->consumed,
            'due_date' => $this->due_date,
            'late_fee_interest' => $this->late_fee_interest,
            'limit' => $this->limit,
        ];
    }
}
