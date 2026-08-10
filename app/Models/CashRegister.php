<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string $uuid
 * @property string $user_uuid
 * @property float $opening_balance
 * @property float $closing_balance
 * @property float $expected_balance
 * @property bool $status
 * @property Carbon $opened_at
 * @property Carbon $closed_at
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 *
 * @property-read Sale[] $sales
 */
class CashRegister extends Model
{
    use SoftDeletes;
    use HasUuids;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'user_uuid',
        'opening_balance',
        'closing_balance',
        'expected_balance',
        'status',
        'opened_at',
        'closed_at',
    ];


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
}
