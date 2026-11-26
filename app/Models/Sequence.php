<?php

namespace App\Models;

use App\Enums\SequenceSaleTypeEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $code codigo unido para cada registro
 * @property SequenceSaleTypeEnum $type Tipo de registro
 * @property int $from numero inicial
 * @property int $next numero siguiente
 * @property int $to numero final
 * @property int $advise mensaje de alerta segun la cantidad prestablecida
 * @property string $num_request numero de solicitud
 * @property string $num_authorization numero de autorizacion
 * @property string $date_request Fecha de solicitud
 * @property string|null $date_expire Fecha de expiracion
 * @property bool $status Estado del Item
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereAdvise($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereDateExpire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereDateRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereNext($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereNumAuthorization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereNumRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence withoutTrashed()
 * @mixin \Eloquent
 */

class Sequence extends Model
{

    use softDeletes;
    use HasUuids;
    use LogsActivity;

    //Tabla a utilizar
    protected $table = 'sequences';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';


    //Datos a llenar masivamente
    protected $fillable = [
        'code',
        'type',
        'from',
        'next',
        'to',
        'advise',
        'num_request',
        'num_authorization',
        'date_request',
        'date_expire',
        'status',
        'deleted_at',
        'uuid'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * @var \class-string[]
     */
    protected $casts = [
        'type' => SequenceSaleTypeEnum::class,
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
        $total = self::withTrashed()->where('status', true)->count();


        // Generar el proximo ID
        $nextID = $total ? $total + 1 : 1;

        // Devolver los datos
        $code = Str::upper(config('appconfig.seqCode'));


        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }


}
