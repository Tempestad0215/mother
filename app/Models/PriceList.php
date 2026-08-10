<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property-read PriceListProduct $pivot
 */

class PriceList extends Model
{
    use SoftDeletes;
    use HasUuids;


    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'currency',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'status' => 'boolean',
        ];
    }


    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class,'price_list_products')
            ->using(PriceListProduct::class);
    }
}
