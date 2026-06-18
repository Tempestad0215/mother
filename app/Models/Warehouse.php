<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $location
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 *
 *
 * @property-read WarehouseProduct $pivot
 * @property-read PurchaseReceiptsItem $receiptsItem
 */
class Warehouse extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasUuids;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    private static ?Collection $currentInstance = null;

    /**
     * @var array
     */
    protected $fillable = [
        'prefix',
        'name',
        'description',
        'location'
    ];

    /**
     * Summary of hidden
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    /**
     * Summary of products
     * @return BelongsToMany<Product, Warehouse, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class,'warehouse_products')
            ->withPivot(
                'stock_quantity',
                'committed',
                'min_stock',
                'max_stock',
                'reorder_level',
                'is_active',
                'last_counted_at'
            )->withTimestamps();
    }


    /**
     * Summary of purchaseItem
     * @return HasMany<PurchaseItem, Warehouse>
     */
    public function purchaseItem(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'warehouse_uuid', 'uuid');
    }

    /**
     * @return HasMany
     */
    public function creditItem():HasMany
    {
        return $this->hasMany(CreditNoteItem::class, 'warehouse_uuid', 'uuid');
    }

    /**
     * Summary of receiptItem
     * @return HasMany<PurchaseReceiptsItem, Warehouse>
     */
    public function receiptItem(): HasMany
    {
        return $this->hasMany(PurchaseReceiptsItem::class);
    }


    public static function getAllCached(): Collection
    {
        if(self::$currentInstance !== null) return self::$currentInstance;

        self::$currentInstance = Cache::remember('app_warehouses', 86400, function () {
           return self::get();
        });

        return self::$currentInstance;
    }


    /**
     * @return void
     */
    protected static function booted():void
    {
        $clearCache = function () {
            Cache::forget('app_warehouses');
            self::$currentInstance = null; // Limpiamos también la instancia en memoria por si acaso
        };

        static::created($clearCache);
        static::updated($clearCache);
        static::deleted($clearCache);
    }
}
