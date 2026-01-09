<?php

namespace App\Models;

use App\Enums\CompanyTypeEnum;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;


/**
 *
 * @mixin EloquentBuilder
 * @mixin QueryBuilder
 *
 * @property int id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $logo
 * @property string $website
 * @property string $company_id
 * @property array $tax
 * @property array $unit
 * @property CompanyTypeEnum $company_type
 * @property string $fiscal_year
 * @property boolean $status
 * @property boolean $save_cost
 * @property boolean $sequence
 * @property Date $deleted_at
 */



class Setting extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasFactory;

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
        'sequence'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'tax' => AsArrayObject::class,
        'status' => 'boolean',
        'save_cost' => 'boolean',
        'sequence' => 'boolean',
    ];


    public function tax():Attribute
    {
        return Attribute::make(
            get: fn (array $value) => "funciona"
        );
    }


    /**
     * Relacion polimorfica para la imagen
     * @return MorphOne
     */
    public function image():MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }


}
