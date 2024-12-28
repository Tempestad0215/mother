<?php

namespace App\Models;

use App\Enums\StatusAccountEnum;
use App\Enums\TypeAccountEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string uuid;
 * @property string accountable_type;
 * @property string accountable_id;
 * @property TypeAccountEnum type;
 * @property float amount;
 * @property float balance ;
 * @property int due_date;
 * @property float late_fee
 * @property StatusAccountEnum status;
 * @property string created_at;
 * @property string updated_at;
 * @property string deleted_at;
 *
 */
class Account extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasUuids;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;


    /*
     * Guardar los datos
     */
    protected $fillable = [
        'accountable_id',
        'accountable_type',
        'type',
        'amount',
        'balance',
        'due_date',
        'late_fee',
        'status',
    ];


    /**
     * Formatear los datos
     * @var string[]
     */
    protected $casts = [
        'type' => TypeAccountEnum::class,
        'status' => 'boolean'
    ];


    /**
     * Relacion polimorficas para las cuentas
     * @return MorphTo
     */
    public function auditable():MorphTo
    {
        return $this->morphTo();
    }
}
