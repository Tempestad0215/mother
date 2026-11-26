<?php

namespace App\Models;

use App\Enums\CashMovementType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $uuid
 * @property string $cash_register_uuid
 * @property CashMovementType $type
 * @property numeric $amount
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \App\Models\CashRegister|null $cashRegister
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement whereCashRegisterUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashMovement withoutTrashed()
 * @mixin \Eloquent
 */
class CashMovement extends Model
{
    use SoftDeletes;
    use HasUuids;
    use LogsActivity;


    /**
     * @var string
     */
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
        'cash_register',
        'type',
        'amount',
        'comment',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    protected $casts = [
        'type' => CashMovementType::class
    ];

    /**
     * @return BelongsTo
     */
    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }
}
