<?php

namespace App\Models;

use App\Enums\TransCountEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $account_co_id Relacion de cuenta
 * @property numeric $amount Monto
 * @property string $type Tipo cuenta
 * @property numeric $debit Monto de debito
 * @property numeric $credit Monto de credito
 * @property string $date Fecha y hora
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo whereAccountCoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransCo withoutTrashed()
 * @mixin \Eloquent
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
