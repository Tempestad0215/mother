<?php

namespace App\Models;

use App\Enums\AccountCountEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $code Codigo
 * @property string $name Nombre
 * @property string $type Tipo cuenta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ACO withoutTrashed()
 * @mixin \Eloquent
 */
class ACO extends Model
{
    use SoftDeletes;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'account_cos';



    /**
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'type',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
