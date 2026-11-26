<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $credit_note_uuid
 * @property string $sale_uuid
 * @property numeric $applied_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteSale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteSale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteSale onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteSale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteSale whereAppliedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteSale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteSale whereCreditNoteUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteSale whereSaleUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteSale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteSale withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNoteSale withoutTrashed()
 * @mixin \Eloquent
 */
class CreditNoteSale extends Model
{
    use SoftDeletes;
    use LogsActivity;


    protected $table = 'credit_note_sale';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }



}
