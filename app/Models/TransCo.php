<?php

namespace App\Models;

use App\Enums\TransCountEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;


/**
 * @property int id
 * @property ACO account_co_id
 * @property float debit
 * @property float credit
 * @property TransCountEnum type
 * @property Carbon date
 * @property Carbon deleted_at
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class TransCo extends Model
{
    use SoftDeletes;
    use LogsActivity;

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
