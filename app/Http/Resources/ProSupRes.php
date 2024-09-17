<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $type
 * @property boolean $inventoried
 * @property boolean $status
 * @property string $code
 * @property string $name
 * @property null|string $description
 * @property string $unit
 * @property float $stock
 * @property float $reserved
 * @property float $cost
 * @property float $price
 * @property string $sku
 * @property string $bar_code
 * @property float $weight
 * @property string $dimensions
 * @property string $brand
 * @property float $discount
 * @property float $tax_rate
 * @property string $comment
 * @property bool $close_table
 * @property  int $supplier_id
 * @property int $category_id
 * @property string $created_at
 * @property string $updated_at
 * @property Category $category
 * @property Supplier $supplier
 *
 * @method static create(mixed $validated)
 */
class ProSupRes extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'unit' => $this->unit,
            'stock' => $this->stock,
            'type' => $this->type,
            'inventoried' => $this->inventoried,
            'cost' => $this->cost,
            'sku' => $this->sku,
            'bar_code' => $this->bar_code,
            'weight' => $this->weight,
            'dimensions' => $this->dimensions,
            'brand' => $this->brand,
            'price' => $this->price,
            'tax_rate' => $this->tax_rate,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
