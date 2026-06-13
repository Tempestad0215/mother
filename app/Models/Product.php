<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property string $uuid
 * @property string $type
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $unit_uuid
 * @property float $cost
 * @property string $sku
 * @property string $bar_code
 * @property float $weight
 * @property string $dimensions
 * @property string $brand_uuid
 * @property float $discount
 * @property float $discount_amount
 * @property float $benefits
 * @property float $benefits_rate
 * @property string $comment
 * @property string $default_price_list
 * @property bool $inventoried
 * @property bool $status
 * @property bool $has_fraction
 * @property bool $has_special
 * @property bool $has_promotion
 * @property bool $has_tax
 * @property bool $handle_warehouse
 * @property string $supplier_uuid
 * @property string $category_uuid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property-read Collection<int, PriceList[] > $price_list
 * @property-read Brand $brand
 * @property-read Tax $tax
 * @property-read PurchaseReceiptsItem $receiptsItem
 * @method static create(mixed $validated)
 */
class Product extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasUuids;


    /**
     * @var mixed|string
     */
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Datos para guardar automatico
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'unit_uuid',
        'cost',
        'supplier_uuid',
        'category_uuid',
        'sku',
        'bar_code',
        'weight',
        'dimensions',
        'brand_uuid',
        'is_service',
        'discount',
        'discount_amount',
        'benefits',
        'benefits_rate',
        'tax_uuid',
        'status',
        'comment',
        'inventoried',
        'has_fraction',
        'has_special',
        'has_promotion',
        'has_tax',
        'handle_warehouse',
        'default_price_list',
        'default_warehouse',
        'purchase_pending'
    ];


    /**
     * Ocultar los datos
     * @var string[]
     */
    protected $hidden = [
        'created_at',
        'update_at'
    ];


    /**
     * Formatear los datos
     * @var string[]
     */
    protected $casts = [
        'status' => 'boolean',
        'has_fraction' => 'boolean',
        'inventoried' => 'boolean',
        'has_special' => 'boolean',
        'has_discount' => 'boolean',
        'has_promotion' => 'boolean',
        'has_tax' => 'boolean',
        'close_table' => 'boolean',
        'is_service' => 'boolean',
        'handle_warehouse' => 'boolean',
    ];


    /**
     * @return void
     */
    protected static function boot(): void
    {
        // Llamar el metodo principal
        parent::boot();

    }


    /***
     * @return BelongsToMany
     */
    public function priceList(): BelongsToMany
    {
        return $this->belongsToMany(PriceList::class, 'price_list_products')
            ->withPivot([
                'price',
                'min_price',
                'promotional_price'
            ]);
    }


    /***
     * @return BelongsToMany
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_products')
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
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }


    /**
     * @return mixed
     */
    public function getTotalAttribute()
    {
        return $this->warehouses->sum('pivot.stock_quantity');
    }

    /**
     * @return mixed
     */
    public function getTotalAvailableAttribute()
    {
        return $this->warehouses->sum(function ($warehouse) {
            return $warehouse->pivot->stock_quantity - $warehouse->pivot->committed_stock;
        });
    }


    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * @return BelongsTo
     */
    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
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
     * @return HasMany
     */
    public function CreditNoteItem(): HasMany
    {
        return $this->hasMany(CreditNoteItem::class);
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany
     */
    //Transacciones
    public function trans(): HasMany
    {
        return $this->hasMany(ProductTransaction::class, 'product_uuid', 'uuid');
    }

    /**
     * @return HasOne
     */
    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    /**
     * @return MorphMany
     */
    public function movements(): MorphMany
    {
        return $this->morphMany(InventoryMovement::class, 'movementable');
    }


    /**
     * @return HasMany
     */
    public function movement(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }


}
