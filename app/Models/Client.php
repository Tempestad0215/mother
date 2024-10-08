<?php

namespace App\Models;

use App\Enums\ClientTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string $code
 * @property string $name
 * @property string $phone
 * @property string $personal_id
 * @property string $email
 * @property string $address
 * @property boolean $status
 * @property int $type
 * @property float $credit_limit
 * @property integer $credit_day
 * @property float $credit_available
 * @property float $credit_consumed
 * @property float $credit_expired
 * @property float $advance_amount
 * @property float $advance_date
 * @property float $advance_expire
 * @property float $advance_consumed
 * @property float $advance_available
 */

class Client extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'personal_id',
        'phone',
        'email',
        'address',
        'status',
        'type'
    ];


    protected $casts = [
        'type' => ClientTypeEnum::class,
        'status'=> 'boolean',
    ];


    /**
     * Relaciones
     */
    public function comment():MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable');
    }



    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo en todo
        static::creating(function ($client) {
            $client->code = self::generateCode();
        });
    }




    /**
     * @return string
     */
    // funcion para generar el codigo
    private static function generateCode():string
    {
        // Obtener el ultimo registros
        $last = self::orderBy('id','desc')->first();

        // Generar el proximo ID
        $nextID = $last ? $last->id + 1 : 1;

        // Devolver los datos
        $code = config('appconfig.cliCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }


}
