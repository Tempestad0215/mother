<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class WarehouseProduct extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasUuids;


    protected $fillable = [
        'committed_stock',
        'warehouse_id',
        'stock_quantity',
        'min_stock',
        'max_stock',
        'is_active',
        'reorder_level',
        'purchase_pending',
        'product_uuid',
        'warehouse_uuid',
    ];

    protected function casts(): array
    {
        return [
            'product_uuid' => 'string',
            'warehouse_uuid' => 'string',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return int
     */
    public function getAvailableStockAttribute(): int
    {
        return $this->stock_quantity - ($this->committed_stock ?? 0);
    }

    /**
     * @return bool
     */
    public function needsReorder(): bool
    {
        return $this->getAvailableStockAttribute() <= $this->reorder_level;
    }
}
