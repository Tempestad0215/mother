<?php

namespace App\Models;

use App\Enums\TRCOTYEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property int id
 * @property ACO account_co_id
 * @property float debit
 * @property float credit
 * @property TRCOTYEnum type
 * @property Carbon date
 * @property Carbon deleted_at
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class TransCo extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'account_co_id',
        'amount',
        'credit',
        'debit',
        'type',
        'date'
    ];
}
