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
        'uuid',
        'product_id',
        'committed_stock',
        'warehouse_id',
        'stock_quantity',
        'min_stock',
        'max_stock',
        'is_active',
        'reorder_level',
    ];

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'product_id' => 'string',
            'warehouse_id' => 'string',
            'is_active' => 'boolean',
        ];
    }

    // Helpers
    public function getAvailableStockAttribute(): int
    {
        return $this->stock_quantity - ($this->committed_stock ?? 0);
    }

    public function needsReorder(): bool
    {
        return $this->getAvailableStockAttribute() <= $this->reorder_level;
    }
}
