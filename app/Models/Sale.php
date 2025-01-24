<?php

namespace App\Models;

use App\Enums\SalePaymentTypeEnum;
use App\Enums\SaleTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * @property int id
 * @property string code
 * @property string invoice_type
 * @property string ncf
 * @property string ncf_m
 * @property string client_rnc
 * @property string client_name
 * @property int client_id
 * @property float discount_amount
 * @property float tax
 * @property float sub_total
 * @property float amount
 * @property boolean status
 * @property SaleTypeEnum type
 * @property bool close_table
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 * @property ProTrans[] infoSale
 * @property SalePaymentTypeEnum type_payment
 * @property float received
 * @property float returned
 * @property string[] credit_notes
 * @property float credit_notes_amount
 * @property Audit audits
 */


class Sale extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;

    // La tabla que se ve a utilizar
    protected $table = 'sales';


    /**
     * Guardar los datos
     * @var array
     */
    protected $fillable = [
        'ncf',
        'invoice_type',
        'code',
        'client_name',
        'client_id',
        'client_rnc',
        'discount_amount',
        'tax',
        'sub_total',
        'amount',
        'type',
        'type_payment',
        'received',
        'returned',
        'status',
        'close_table',
        'credit_notes',
        'credit_notes_amount'
    ];

    //Formatear los datos
    protected  $casts = [
        'status' => 'boolean',
        'close_table' => 'boolean',
        'type' => SaleTypeEnum::class,
        'type_payment' => SalePaymentTypeEnum::class,
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
     * @return HasMany
     *
     */
    public function credit_note():HasMany
    {
        return $this->hasMany(CreditNote::class, 'sale_id','uuid');
    }


    /**
     * @return HasManyThrough
     */
    public function credit_trans():HasManyThrough
    {
        return $this->hasManyThrough(ProTrans::class, CreditNote::class, 'sale_id','credit_note_id','uuid');
    }


    /**
     * Retorno de valor
     * @return HasMany
     */
    public function infoSale():HasMany
    {
        return $this->hasMany(ProTrans::class);
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

    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo los codigos
        static::creating(function ($model) {
            $model->code = self::generateCode();
        });
    }

    /**
     * @return string
     */
    // funcion para generar el codigo
    private static function generateCode():string
    {
        // Obtener el ultimo registros
        $total = self::count();

        // Generar el proximo ID
        $nextID = $total ? $total + 1 : 1;

        // Devolver los datos
        $code = config('appconfig.saleCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }


}
