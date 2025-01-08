<?php

namespace App\Http\Resources;

use App\Enums\PATYEnum;
use App\Models\Comment;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @property int uuid
 * @property string|null contact
 * @property string company_name
 * @property string|null phone
 * @property string|null email
 * @property boolean status
 * @property boolean receive_email
 * @property string account_bank
 * @property PATYEnum type_payment
 * @property Account account
 * @property Comment comment
 *
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
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
            'account' => $this->when(isset($this->account), function () use ($request) {
                return [
                    'uuid' =>  $this->account->uuid,
                    'amount' => $this->account->amount,
                    'due_date' => $this->account->due_date,
                    'balance' => $this->account->balance,
                    'consumed' => (($this->account->amount * 100 ) - ($this->account->balance * 100)) / 100,
                    'late_fee_interest' => $this->account->late_fee,
                ];
            })
        ];
    }
}
