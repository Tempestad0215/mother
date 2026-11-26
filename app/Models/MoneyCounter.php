<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $code Codigo
 * @property numeric $coin_first
 * @property numeric $coin_second
 * @property numeric $coin_third
 * @property numeric $coin_fourth
 * @property numeric $coin_fifth
 * @property numeric $coin_sixth
 * @property numeric $coin_seventh
 * @property numeric $coin_eighth
 * @property numeric $coin_ninth
 * @property numeric $coin_tenth
 * @property numeric $card tarjetas
 * @property numeric $transfer transfer
 * @property numeric $check check
 * @property numeric $other_income otro ingreso
 * @property numeric $expenses gastos
 * @property numeric $cash_withdrawals Dinero en Caja
 * @property numeric $refund Devoluciones
 * @property numeric $other_expenses otro gastos
 * @property numeric $opening_balance BAlance inicial
 * @property numeric $total_coin Total De moneda
 * @property numeric $total_other_coin Total De Otras moneda
 * @property numeric $total_expenses Total de Gastos
 * @property numeric $diff Diferencia
 * @property numeric $total_neto Total De Neto
 * @property string|null $comment Comentario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @method static \Database\Factories\MoneyCounterFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCashWithdrawals($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCoinEighth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCoinFifth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCoinFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCoinFourth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCoinNinth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCoinSecond($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCoinSeventh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCoinSixth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCoinTenth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCoinThird($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereOpeningBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereOtherExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereOtherIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereTotalCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereTotalExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereTotalNeto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereTotalOtherCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyCounter whereUuid($value)
 * @mixin \Eloquent
 */
class MoneyCounter extends Model
{
    use Searchable;
    use HasFactory;
    use LogsActivity;


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


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
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
        $code = config('appconfig.counter');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }


}
