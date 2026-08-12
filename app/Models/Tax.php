<?php

namespace App\Models;

use App\Enums\CacheKeyEnum;
use App\Enums\ModelStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

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
    use HasUuids;
    use LogsActivity;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    private static ?Collection $currentInstances = null;


    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'rate',
        'model_status',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    /**
     * @return Attribute
     */
    public function name():Attribute
    {
        return Attribute::make(
            get: fn(string $value) => strtoupper($value),
            set: fn(string $value) => strtoupper($value),
        );
    }

    /**
     * @return HasMany
     */
    public function products():HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return HasMany
     */
    public function purchaseItem(): HasMany
    {
        return $this->hasMany(PurchaseItem::class,'tax_uuid','uuid');
    }

    /**
     * @return HasMany
     */
    public function receiptsItem(): HasMany
    {
        return $this->hasMany(PurchaseReceiptsItem::class);
    }


    /**
     * @return HasMany
     */
    public function SaleItem(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * @return \class-string[]
     */
    protected function casts(): array
    {
        return [
            'model_status' => ModelStatusEnum::class,
        ];
    }


    public static function getAllCached(): Collection
    {
        if(self::$currentInstances !== null) return self::$currentInstances;

        self::$currentInstances = Cache::remember(CacheKeyEnum::Tax->value, 86400, function () {
           return self::get();
        });

        return self::$currentInstances;
    }

    protected static function booted(): void
    {
        $clearCache = function () {
            Cache::forget(CacheKeyEnum::Tax->value);
            self::$currentInstances = null;
        };

        static::created($clearCache);
        static::updated($clearCache);
        static::deleted($clearCache);
    }
}
