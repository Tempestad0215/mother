<?php

namespace App\Models;

use App\Enums\TypePaymentEnum;
use App\Enums\SaleTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property string $uuid
 * @property string $code
 * @property string $ncf
 * @property string $ncf_m
 * @property string $client_rnc
 * @property string $client_name
 * @property int $client_id
 * @property float $discount_amount
 * @property float $tax
 * @property float $sub_total
 * @property float $amount
 * @property boolean $status
 * @property SaleTypeEnum $type
 * @property bool $close_table
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property ProTrans[] $infoSale
 * @property TypePaymentEnum $type_payment
 * @property float $received
 * @property float $returned
 * @property integer[] $credit_notes
 * @property float $credit_notes_amount
 */


class Sale extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasUuids;


    // La tabla que se ve a utilizar
    protected $table = 'sales';

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';


    /**
     * Guardar los datos
     * @var array
     */
    protected $guarded = [];

    //Formatear los datos
    protected  $casts = [
        'status' => 'boolean',
        'close_table' => 'boolean',
        'type' => SaleTypeEnum::class,
        'type_payment' => TypePaymentEnum::class,
        'credit_notes' => 'array'
    ];


    /*
     * Relaciones
     */

    //Relacion de comentario
    public function comment():MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable');
    }

    /**
     * @return BelongsTo
     */
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id','uuid');
    }

    /**
     *Relacion notas de creditos
     * @return HasMany
     */
    public function credit_note():HasMany
    {
        return $this->hasMany(CreditNote::class,'sale_id','uuid');
    }

    /**
     * Retorno de valor
     * @return HasMany
     */
    public function infoSale():HasMany
    {
        return $this->hasMany(ProTrans::class,'sale_id','uuid');
    }

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

    /**
     * Formataer la fecha de actualizacion
     * @return Attribute
     */
    protected function updatedAt ():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

}
