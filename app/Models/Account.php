<?php

namespace App\Models;

use App\Enums\StatusAccountEnum;
use App\Enums\AccountTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property integer id;
 * @property string accountable_type;
 * @property string accountable_id;
 * @property AccountTypeEnum type;
 * @property float amount;
 * @property float balance ;
 * @property int due_date;
 * @property float late_fee
 * @property StatusAccountEnum status;
 * @property string created_at;
 * @property string updated_at;
 * @property string deleted_at;
 *
 */
class Account extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'accountable_id',
        'accountable_type',
        'type',
        'amount',
        'balance',
        'due_date',
        'late_fee',
        'status',
    ];


    /**
     * Formatear los datos
     * @var string[]
     */
    protected $casts = [
        'type' => AccountTypeEnum::class,
        'status' => 'boolean'
    ];


    /**
     * Relacion polimorficas para las cuentas
     * @return MorphTo
     */
    public function auditable():MorphTo
    {
        return $this->morphTo();
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
        $code = config('appconfig.accCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }
}
