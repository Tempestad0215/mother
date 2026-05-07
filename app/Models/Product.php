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
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
* @property string $type
* @property string $code
* @property string $name
* @property string $description
* @property string $unit
* @property float $cost
* @property float $special_price
* @property float $min_price
* @property float $price
* @property string $sku
* @property string $bar_code
* @property float $weight
* @property string $dimensions
* @property string $brand
* @property float $tax_rate
* @property float $tax
* @property float $discount
* @property float $discount_amount
* @property float $product_no_tax
* @property float $benefits
* @property float $benefits_rate
* @property string $comment
* @property bool $inventoried
* @property bool $status
* @property bool $has_fraction
* @property bool $has_special
* @property bool $has_promotion
* @property bool $has_tax
* @property int $supplier_id
* @property int $category_id
* @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 *
 * @property-read PurchaseReceiptsItem $receiptsItem
 * @method static create(mixed $validated)
 */
class Product extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasUuids;


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
        'unit_id',
        'cost',
        'special_price',
        'min_price',
        'price',
        'supplier_id',
        'category_id',
        'sku',
        'bar_code',
        'weight',
        'dimensions',
        'branch_id',
        'is_service',
        'discount',
        'discount_amount',
        'product_tax',
        'product_no_tax',
        'benefits',
        'benefits_rate',
        'tax_id',
        'tax_rate',
        'tax',
        'status',
        'comment',
        'inventoried',
        'has_fraction',
        'has_special',
        'has_promotion',
        'has_tax'
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
        'is_service' => 'boolean'
    ];


    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

    }




//    Relacion para el stock
    public function warehouses():BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_products')
            ->withPivot(
                'stock_quantity',
                'committed_stock',
                'min_stock',
                'max_stock',
                'reorder_level',
                'is_active',
                'last_counted_at'
            )->withTimestamps();
    }

    public function getTotalAttribute()
    {
        return $this->warehouses->sum('pivot.stock_quantity');
    }

    // Stock disponible total
    public function getTotalAvailableAttribute()
    {
        return $this->warehouses->sum(function($warehouse) {
            return $warehouse->pivot->stock_quantity - $warehouse->pivot->committed_stock;
        });
    }

    /**
     * @return BelongsTo
     */
    public function supplier():BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function receiptsItem(): HasMany
    {
        return $this->hasMany(PurchaseReceiptsItem::class);
    }

    public function SaleItem(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //Transacciones
    public function trans():HasMany
    {
        return $this->hasMany(ProTrans::class, 'product_id','uuid');
    }

    public function inventory():HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    public function movements():MorphMany
    {
        return $this->morphMany(InventoryMovement::class, 'movementable');
    }


    /**
     * @return HasMany
     */
    public function movement():HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }



}
