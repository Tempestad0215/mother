<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $user_uuid
 * @property numeric $opening_balance
 * @property numeric $closing_balance
 * @property numeric $expected_balance
 * @property bool $status
 * @property int $opened_at
 * @property int|null $closed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property array<array-key, mixed>|null $summary
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashMovement> $movements
 * @property-read int|null $movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sale> $sales
 * @property-read int|null $sales_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereClosingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereExpectedBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereOpenedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereOpeningBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereUserUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashRegister withoutTrashed()
 * @mixin \Eloquent
 */
class CashRegister extends Model
{
    use SoftDeletes;
    use HasUuids;
    use LogsActivity;

    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'uuid',
        'user_uuid',
        'opening_balance',
        'closing_balance',
        'expected_balance',
        'summary',
        'status',
        'opened_at',
        'closed_at',
    ];


    public function getActivitylogOptions():LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }


    /**
     * @return HasMany
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(CashMovement::class);
    }

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'opened_at' => 'timestamp',
            'closed_at' => 'timestamp',
            'summary' => 'array'
        ];
    }


    /**
     * Verifica si el usuario tiene una caja activa válida (menos de 12 horas abierta).
     *
     * @return bool
     */
    public static function checkAvailable(): bool
    {
        return self::where('user_uuid', auth()->user()->uuid)
            ->where('status', true)
            ->where('created_at', '>', now()->subHours(12)) // Abierta hace menos de 12 horas
            ->exists();
    }

    /**
     * Retorna el objeto CashRegister si hay una caja que requiere arqueo obligatorio, o null si
     */
    public static function hasExpiredRegister(): ?self
    {
        return self::where('user_uuid', auth()->user()->uuid)
            ->where('status', true)
            ->where('created_at', '<=', now()->subHours(12)) // Superó las 12 horas
            ->first(); // Nos interesa recuperar la caja específica para poder cerrarla
    }


    /**
     * Obtiene la caja abierta del usuario desde la caché (o la busca si no existe)
     */
    public static function getActiveForUser(string $userUuid): ?self
    {

        return Cache::remember("user_{$userUuid}_active_cash_register", now()->addHours(12), function () use ($userUuid) {
            return self::where('user_uuid', $userUuid)
                ->where('status', true)
                ->first();
        });
    }

    /**
     * Limpia la caché de la caja de un usuario específico
     */
    public static function clearCacheForUser(string $userUuid): void
    {
        Cache::forget("user_{$userUuid}_active_cash_register");
    }



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
