<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'description' => $this->description,
            'name' => $this->name,
            'prefix' => $this->prefix,
            'code' => $this->code,
            'uuid' => $this->uuid,
            'product_count' => $this->product_count,
            'audits_count' => $this->audits_count,
        ];
    }
}
