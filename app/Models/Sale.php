<?php

namespace App\Models;

use App\Enums\SalePaymentEnum;
use App\Enums\SaleTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
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
 * @property SalePaymentEnum $type_payment
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


    // La tabla que se ve a utilizar
    protected $table = 'sales';


    // Datos para actualizar masivamente
    protected $fillable = [
        'code',
        'ncf',
        'ncf_m',
        'client_name',
        'client_rnc',
        'client_id',
        'discount_amount',
        'tax',
        'sub_total',
        'amount',
        'type',
        'status',
        'close_table',
        'credit_notes',
        'credit_notes_amount',
        'returned',
        'received'
    ];


    //Formatear los datos
    protected  $casts = [
        'status' => 'boolean',
        'close_table' => 'boolean',
        'type' => SaleTypeEnum::class,
        'type_payment' => SalePaymentEnum::class,
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
        return $this->belongsTo(Client::class);
    }


    public function credit_note():HasMany
    {
        return $this->hasMany(CreditNote::class);
    }


    //Relacion para los datos de las ventas

    /**
     * Retorno de valor
     * @return HasMany
     */
    public function infoSale():HasMany
    {
        return $this->hasMany(ProTrans::class);
    }



    //Formatear los datos

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




    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo
        static::creating(function ($sale) {
            $sale->code = self::generateCode($sale->type);
        });
    }



    /**
     * @param SaleTypeEnum $type
     * @return string
     *
     */
    // funcion para generar el codigo
    private static function generateCode(SaleTypeEnum $type):string
    {
        // Obtener el ultimo registros
        $last = self::where('type', $type)->orderBy('id','desc')->first();

        // Generar el proximo ID
        $nextID = $last ? $last->id + 1 : 1;



        if ($type->value === SaleTypeEnum::COTIZACION->value)
        {
            $code = config('appconfig.quoCode');
        }else{

            $code = config('appconfig.saleCode');
        }


        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }

}
