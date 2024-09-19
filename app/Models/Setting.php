<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property string $fiscal_year
 * @property boolean $status
 * @property boolean $save_cost
 *
 *
 *
 *
 */



class Setting extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

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
        'status',
        'save_cost'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'tax' => 'json',
        'unit' => 'array',
        'status' => 'boolean',
        'save_cost' => 'boolean'
    ];
}
