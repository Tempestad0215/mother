<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
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
class CashRegister extends Model implements Auditable
{
    use SoftDeletes;
    use HasUuids;
    use \OwenIt\Auditing\Auditable;

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
     * Verifica y retorna la caja activa del usuario para el día de hoy.
     * * @return bool
     */
    public static function checkAvailable():bool
    {
        // Buscamos una caja donde:
        return self::where('user_uuid', auth()->user()->uuid) // Sea del usuario actual
        ->where('status', true)                          // Esté abierta (si true significa activa/abierta)
        ->whereDate('created_at', Carbon::today())        // Haya sido abierta hoy
        ->exists();                                       // Retorna el objeto o null si no hay
    }

}
