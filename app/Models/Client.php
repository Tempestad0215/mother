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
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string $uuid
 * @property string $name
 * @property SequenceSaleTypeEnum $type_rnc
 * @property string $phone
 * @property string $personal_id
 * @property string $email
 * @property ClientDocumentEnum $document
 * @property string $address
 * @property boolean $status
 * @property float $limit
 * @property integer $due_date
 * @property ClientTypeEnum $type
 * @property float $late_fee_interest
 * @property float $balance
 * @property float $consumed
 * @property ClientTypePriceEnum $type_price
 * @property boolean $receive_email
 * @property string $comment
 * @property Date $deleted_at
 * @property Date $created_at
 * @property Date $updated_at
 */

class Client extends Model implements Auditable
{
    use Searchable;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasUuids;

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
