<?php

namespace App\Models;

use App\Enums\CacheKeyEnum;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $name Nombre de la empresa
 * @property string $email Correo electronico
 * @property string|null $phone Telefono
 * @property string|null $address Direccion
 * @property string|null $website Sitio web
 * @property string|null $company_id ID de empresa
 * @property bool $save_cost Proteger el costo
 * @property bool $sequence Manejar comprobante
 * @property string|null $image_path
 * @property string|null $fiscal_year Año Fiscal
 * @property bool $status Estado
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \App\Models\Image|null $image
 * @property-read mixed $tax
 * @method static \Database\Factories\SettingFactory factory($count = null, $state = [])
 * @method static EloquentBuilder<static>|Setting newModelQuery()
 * @method static EloquentBuilder<static>|Setting newQuery()
 * @method static EloquentBuilder<static>|Setting onlyTrashed()
 * @method static EloquentBuilder<static>|Setting query()
 * @method static EloquentBuilder<static>|Setting whereAddress($value)
 * @method static EloquentBuilder<static>|Setting whereCompanyId($value)
 * @method static EloquentBuilder<static>|Setting whereCreatedAt($value)
 * @method static EloquentBuilder<static>|Setting whereDeletedAt($value)
 * @method static EloquentBuilder<static>|Setting whereEmail($value)
 * @method static EloquentBuilder<static>|Setting whereFiscalYear($value)
 * @method static EloquentBuilder<static>|Setting whereImagePath($value)
 * @method static EloquentBuilder<static>|Setting whereName($value)
 * @method static EloquentBuilder<static>|Setting wherePhone($value)
 * @method static EloquentBuilder<static>|Setting whereSaveCost($value)
 * @method static EloquentBuilder<static>|Setting whereSequence($value)
 * @method static EloquentBuilder<static>|Setting whereStatus($value)
 * @method static EloquentBuilder<static>|Setting whereUpdatedAt($value)
 * @method static EloquentBuilder<static>|Setting whereUuid($value)
 * @method static EloquentBuilder<static>|Setting whereWebsite($value)
 * @method static EloquentBuilder<static>|Setting withTrashed(bool $withTrashed = true)
 * @method static EloquentBuilder<static>|Setting withoutTrashed()
 * @mixin \Eloquent
 */



class Setting extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasFactory;
    use HasUuids;
    use LogsActivity;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    private static ?Setting $currentInstance = null;



    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'logo',
        'website',
        'company_id',
        'fiscal_year',
        'status',
        'save_cost',
        'sequence',
        'add_tax',
        'tax',
        'unit'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'status' => 'boolean',
        'save_cost' => 'boolean',
        'sequence' => 'boolean',
        'add_tax' => 'boolean',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }




    /**
     * Relacion polimorfica para la imagen
     * @return MorphOne
     */
    public function image():MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }


    /**
     * @return Setting|null
     */
    public static function getGlobal():?Setting
    {
        if(self::$currentInstance !== null) return self::$currentInstance;

        self::$currentInstance = \Cache::remember(CacheKeyEnum::Settings->value, 86400, function () {
           return self::first();
        });

        return self::$currentInstance;
    }


}
