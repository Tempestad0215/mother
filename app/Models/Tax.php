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
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $uuid
 * @property string $name
 * @property string|null $description
 * @property numeric $rate
 * @property ModelStatusEnum $model_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleItem> $SaleItem
 * @property-read int|null $sale_item_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseItem> $purchaseItem
 * @property-read int|null $purchase_item_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseReceiptsItem> $receiptsItem
 * @property-read int|null $receipts_item_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereModelStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tax withoutTrashed()
 * @mixin \Eloquent
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


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }


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
