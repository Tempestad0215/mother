<?php

namespace App\Models;

use App\Enums\ProductTransType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property int $id
 * @property string $code
 * @property int $product_id
 * @property string $product_name
 * @property int $sale_id
 * @property int $credit_note_id
 * @property float $stock
 * @property float $reserved
 * @property float $price
 * @property float $min_price
 * @property float $special_price
 * @property float $discount
 * @property float $discount_amount
 * @property float $tax_rate
 * @property float $tax
 * @property float $tax_amount
 * @property float $amount
 * @property boolean $status
 * @property ProductTransType $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */

class ProTrans extends Model implements Auditable
{
    /**
     *
     */
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasUuids;

    protected $table = 'pro_trans';

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';


    /*
     * Almacenar todos los datos
     */
    protected $guarded = [];
    /**
     * @var string[]
     */
//    protected $fillable = [
//        'product_id',
//        'product_name',
//        'sale_id',
//        'credit_note_id',
//        'stock',
//        'price',
//        'min_price',
//        'special_price',
//        'discount',
//        'discount_amount',
//        'tax_rate',
//        'tax',
//        'tax_amount',
//        'amount',
//        'type',
//        'status'
//    ];

    //formatear los datos
    protected $casts = [
        'status' => 'boolean',
        'type' => ProductTransType::class
    ];


    //Campo Oculto
    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    /*
     * Relacionar los datos
     */

    public function sale():BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * @return BelongsTo
     */
    public function product():belongsTo
    {
        return $this->belongsTo(Product::class, 'product_id','uuid');
    }

    /**
     * @return BelongsTo
     */
    public function creditNote():belongsTo
    {
        return $this->belongsTo(CreditNote::class);
    }


    /*
     * Fomatear los datos
     */
    /**
     * Formatear la fehca de creacion
     * @return Attribute
     */
    protected function createdAt ():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

}
