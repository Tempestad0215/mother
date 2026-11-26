<?php

namespace App\Models;

use App\Traits\HasCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaProduct searchByCode($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaProduct whereCode($code)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaProduct withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaProduct withoutTrashed()
 * @mixin \Eloquent
 */
class PaProduct extends Model
{
    use SoftDeletes;
    use HasCode;
    use LogsActivity;

    protected $fillable = [
        'code',
        'supplier_uuid',
        'document_date',
        'comment',
        'total',
        'tax',
        'sub_total',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
