<?php

namespace App\Models;

use App\Enums\ModelStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $rate
 * @property ModelStatusEnum $status
 *
 *
 * @property-read PurchaseItem $purchaseItem
 * @property-read PurchaseReceiptsItem $receiptsItem
 *
 */

class Tax extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'rate',
        'model_status',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function name():Attribute
    {
        return Attribute::make(
            get: fn(string $value) => strtoupper($value),
            set: fn(string $value) => strtoupper($value),
        );
    }

    public function purchaseItem(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function receiptsItem(): HasMany
    {
        return $this->hasMany(PurchaseReceiptsItem::class);
    }

    protected function casts(): array
    {
        return [
            'model_status' => ModelStatusEnum::class,
        ];
    }
}
