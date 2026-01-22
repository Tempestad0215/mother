<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @see \App\Models\PurchaseItem */

/**
 * @property int ind
 * @property
 */
class PurchaseItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
        ];
    }
}
