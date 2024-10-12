<?php

namespace App\Models;

use App\Enums\SalePaymentEnum;
use App\Enums\SaleTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

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
 * @property float $n_available
 * @property float $n_used
 * @property boolean $status
 * @property Carbon $created_at,
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property ProTrans[] $trans
 * @property Sale[] $sale,
 * @property SalePaymentEnum $type_payment,
 * @property float $received
 * @property float $returned
 */

class CreditNote extends Model
{
    use SoftDeletes;


    // La tabla que se ve a utilizar
    protected $table = 'credit_notes';


    // Datos para actualizar masivamente
    protected $fillable = [
        'code',
        'ncf',
        'ncf_m',
        'client_name',
        'client_rnc',
        'client_id',
        'sale_id',
        'discount_amount',
        'tax',
        'sub_total',
        'amount',
        'n_available',
        'n_used',
        'type',
        'status',
    ];




    /*
     * Relaciones
     */
    public function comment():MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable');
    }

    /**
     * Relacion de nota de credito
     * @return HasMany
     */
    public function trans():HasMany
    {
        return $this->hasMany(ProTrans::class);

    }

    public function sale():belongsTo
    {
        return $this->belongsTo(Sale::class);
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

        $code = config('appconfig.saleRet');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }


}
