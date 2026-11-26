<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $code Codigo
 * @property int $trans_co_id Relacion de transcciones
 * @property numeric $discount_amount Total Descuento
 * @property numeric $tax Total Tax
 * @property numeric $sub_total Sub Total
 * @property numeric $amount Total General
 * @property bool $close_table Estado del Cuenta
 * @property \App\Models\Comment|null $comment Comentario
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereCloseTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereTransCoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeletedSale withoutTrashed()
 * @mixin \Eloquent
 */
class DeletedSale extends Model
{

    use softDeletes;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'deleted_sales';


    /**
     * @var string[]
     */
    protected  $fillable = [
        'code',
        'sale_uuid',
        'info',
        'discount_amount',
        'tax',
        'sub_total',
        'amount',
        'status',
        'close_table'
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * @var string[]
     */
    protected $casts = [
        'info' => 'json'
    ];


    /*
     * Relaciones
     */

    //Comentario
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
        $code = config('appconfig.delSale');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }


}
