<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount query()
 * @mixin \Eloquent
 */
class InventoryCount extends Model
{
    //

    use LogsActivity;

    protected $fillable = [
        'product_id',
        'current_stock',
        'system_stock',
        'difference',
        'description'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
