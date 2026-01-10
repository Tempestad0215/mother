<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property int id
 * @property string code
 * @property string from
 * @property string to
 * @property float coin_first
 * @property float coin_second
 * @property float coin_third
 * @property float coin_fourth
 * @property float coin_fifth
 * @property float coin_sixth
 * @property float coin_seventh
 * @property float coin_eighth
 * @property float coin_ninth
 * @property float coin_tenth
 * @property float card
 * @property float transfer
 * @property float check
 * @property float other_income
 * @property float expenses
 * @property float cash_withdrawals
 * @property float refund
 * @property float other_expenses
 * @property float opening_balance
 * @property float total_coin
 * @property float total_other_coin
 * @property float total_expenses
 * @property float diff
 * @property float total_neto
 *
 */
class MoneyCounter extends Model implements Auditable
{
    use Searchable;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    //table
    protected $table = 'money_counter';



    //Campos para rellenar
    protected $fillable = [
        'coin_first',
        'coin_second',
        'coin_third',
        'coin_fourth',
        'coin_fifth',
        'coin_sixth',
        'coin_seventh',
        'coin_eighth',
        'coin_ninth',
        'coin_tenth',
        'card',
        'transfer',
        'check',
        'other_income',
        'expenses',
        'cash_withdrawals',
        'refund',
        'other_expenses',
        'opening_balance',
        'total_coin',
        'total_other_coin',
        'total_expenses',
        'diff',
        'total_neto',
    ];



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
        $code = config('appconfig.counter');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }


}
