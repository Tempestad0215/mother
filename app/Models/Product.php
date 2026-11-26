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
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $uuid
 * @property string $code Codigo
 * @property string $name nombre
 * @property string|null $description descripcion
 * @property string|null $location ubicacion
 * @property string|null $sku codigo unico
 * @property string|null $bar_code codigo de barra
 * @property float $weight peso
 * @property string|null $dimensions dimensiones
 * @property numeric $cost costo
 * @property numeric $benefits Beneficios del producto
 * @property numeric $benefits_rate Porcentaje de Margen
 * @property numeric $discount Porcentaje de descuento
 * @property numeric $discount_amount Descuento Total
 * @property string $default_warehouse
 * @property string $default_price_list
 * @property string $category_uuid Categoria Item
 * @property string $supplier_uuid Suplidor del Item
 * @property bool $is_service Tipo de Servicio
 * @property bool $inventoried Maneja Inventario
 * @property bool $status Estado del Item
 * @property bool $has_fraction Se Puede Fraccionar
 * @property bool $has_special Precio Special Activado
 * @property bool $has_discount Aplica para descuento
 * @property bool $has_promotion Aplica para comisiones
 * @property bool $has_tax Aplica para impuestos
 * @property bool $handle_warehouse Aplica para impuestos
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $tax_uuid
 * @property string|null $unit_uuid
 * @property string|null $brand_uuid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CreditNoteItem> $CreditNoteItem
 * @property-read int|null $credit_note_item_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \App\Models\Brand|null $brand
 * @property-read \App\Models\Category|null $category
 * @property-read mixed $total
 * @property-read mixed $total_available
 * @property-read \App\Models\Inventory|null $inventory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryMovement> $movement
 * @property-read int|null $movement_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryMovement> $movements
 * @property-read int|null $movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PriceList> $priceList
 * @property-read int|null $price_list_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseReceiptsItem> $receiptsItem
 * @property-read int|null $receipts_item_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleItem> $saleItem
 * @property-read int|null $sale_item_count
 * @property-read \App\Models\Supplier|null $supplier
 * @property-read \App\Models\Tax|null $tax
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductTransaction> $trans
 * @property-read int|null $trans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Warehouse> $warehouses
 * @property-read int|null $warehouses_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBarCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBenefits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBenefitsRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBrandUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDefaultPriceList($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDefaultWarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHandleWarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHasDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHasFraction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHasPromotion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHasSpecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHasTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereInventoried($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSupplierUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTaxUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnitUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withoutTrashed()
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;
    use softDeletes;
    use HasUuids;
    use LogsActivity;


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
        'sku',
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


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
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
    public function saleItem(): HasMany
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
