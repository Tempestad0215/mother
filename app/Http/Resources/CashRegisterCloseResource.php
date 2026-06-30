<?php

namespace App\Http\Resources;

use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CashRegister */
class CashRegisterCloseResource extends JsonResource
{

    public function toArray(Request $request): array
    {

        $totalExpense = 0;
        $total
        foreach ($this->movements as $movement) {}


        return [
            ... parent::toArray($request)


        ];
    }
}
