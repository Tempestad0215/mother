<?php

namespace App\Http\Resources;

use App\Enums\TypePaymentEnum;
use App\Models\Comment;
use App\Models\Credits;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @property int $uuid
 * @property string|null $contact
 * @property string $company_name
 * @property string|null $phone
 * @property string|null $email
 * @property boolean $status
 * @property boolean $receive_email
 * @property string $account_bank
 * @property TypePaymentEnum $type_payment
 * @property Credits $credit
 * @property Comment $comment
 *
 * @property string $deleted_at
 */
class SupplierResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'contact' => $this->contact,
            'company_name' => $this->company_name,
            'payment' => [
                'value' => $this->type_payment->value,
                'name' => $this->type_payment->name
            ],
            'phone' => $this->phone,
            'email' => $this->email,
            'receive_email' => $this->receive_email,
            'account_bank' => $this->account_bank,
            'credit' => $this->when(isset($this->credit), function () use ($request) {
                return [
                    'uuid' =>  $this->credit->uuid,
                    'limit' => $this->credit->limit,
                    'due_date' => $this->credit->due_date,
                    'balance' => $this->credit->balance,
                    'consumed' => $this->credit->consumed,
                    'late_fee_interest' => $this->credit->late_fee_interest,
                ];
            })
        ];
    }
}
