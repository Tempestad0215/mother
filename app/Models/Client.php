<?php

namespace App\Models;

use App\Enums\ClientDocumentEnum;
use App\Enums\ClientTypeEnum;
use App\Enums\ClientTypePriceEnum;
use App\Enums\SequenceSaleTypeEnum;
use App\Helpers\CodeHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $code Codigo
 * @property SequenceSaleTypeEnum $type_rnc Tipo de RNC
 * @property string $name Nombre
 * @property ClientDocumentEnum $document Documento
 * @property string|null $phone Telefono
 * @property string|null $personal_id ID Personal
 * @property string|null $email Email
 * @property string|null $address Direccion
 * @property ClientTypeEnum $type Tipo
 * @property ClientTypePriceEnum $type_price Tipo precio
 * @property bool $receive_email Recibir email
 * @property string|null $comment Comentario
 * @property bool $status Estado
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \App\Models\Image|null $image
 * @property-read \App\Models\Sale|null $sale
 * @method static \Database\Factories\ClientFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePersonalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereReceiveEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereTypePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereTypeRnc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client withoutTrashed()
 * @mixin \Eloquent
 */

class Client extends Model
{
    use Searchable;
    use HasFactory;
    use softDeletes;
    use HasUuids;
    use LogsActivity;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'type_rnc',
        'document',
        'personal_id',
        'receive_email',
        'phone',
        'email',
        'address',
        'type',
        'comment',
        'status',
        'type_price'
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
        'type' => ClientTypeEnum::class,
        'type_rnc' => SequenceSaleTypeEnum::class,
        'document' => ClientDocumentEnum::class,
        'type_price' => ClientTypePriceEnum::class,
    ];


    /**
     * @return Attribute
     */
    public function email():Attribute
    {
        return Attribute::make(
            get: fn(?string $value):?string => strtolower($value),
            set: fn(?string $value):?string => strtolower($value)
        );
    }

    /**
     * @return MorphOne
     */
    public function image():MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * @return MorphOne
     */
    public function account():MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    /**
     * Cliente
     * @return HasOne
     */
    public function sale():HasOne
    {
        return $this->hasOne(Sale::class);
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
            $model->code = self::generateCode($model);
        });
    }

    /**
     * @param Model $model
     * @return string
     */
    private static function generateCode(Model $model):string
    {
//        Contar los nuemros totales
        $nextNumber = self::withTrashed()->count('uuid') + 1;

        return CodeHelper::generateCode($model, $nextNumber);
    }




}
