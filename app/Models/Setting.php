<?php

namespace App\Models;

use App\Enums\CompanyTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 *
 */



class Setting extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;

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
        'tax',
        'tax.name',
        'tax.value',
        'unit',
        'fiscal_year',
        'company_type',
        'status',
        'save_cost',
        'sequence'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'tax' => 'json',
        'unit' => 'array',
        'status' => 'boolean',
        'save_cost' => 'boolean',
        'sequence' => 'boolean',
        'company_type' => CompanyTypeEnum::class,
    ];
}
