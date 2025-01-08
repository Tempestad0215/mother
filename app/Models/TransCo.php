<?php

namespace App\Models;

use App\Enums\TRCOTYEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string uuid
 * @property ACO account_co_id
 * @property string description
 * @property TRCOTYEnum type
 * @property Carbon date
 */
class TransCo extends Model implements Auditable
{
    use SoftDeletes, HasUuids;
    use \OwenIt\Auditing\Auditable;


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
        'account_co_id',
        'description',
        'amount',
        'type',
        'date'
    ];
}
