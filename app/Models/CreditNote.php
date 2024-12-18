<?php

namespace App\Models;

use App\Enums\TypePaymentEnum;
use App\Enums\SaleTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $ncf
 * @property string $ncf_m
 * @property string $client_rnc
 * @property string $client_name
 * @property int $client_id
 * @property float $discount_amount
 * @property float $tax
 * @property float $sub_total
 * @property float $amount
 * @property float $n_available
 * @property float $n_used
 * @property boolean $status
 * @property Carbon $created_at,
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property ProTrans[] $trans
 * @property Sale[] $sale,
 * @property TypePaymentEnum $type_payment,
 * @property float $received
 * @property float $returned
 */

class CreditNote extends Model
{
    use SoftDeletes;


    // La tabla que se ve a utilizar
    protected $table = 'credit_notes';


    // Datos para actualizar masivamente
    protected $fillable = [
        'code',
        'ncf',
        'ncf_m',
        'client_name',
        'client_rnc',
        'client_id',
        'sale_id',
        'discount_amount',
        'tax',
        'sub_total',
        'amount',
        'n_available',
        'n_used',
        'type',
        'status',
    ];




    /*
     * Relaciones
     */
    public function comment():MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable');
    }

    /**
     * Relacion de nota de credito
     * @return HasMany
     */
    public function trans():HasMany
    {
        return $this->hasMany(ProTrans::class);

    }

    public function sale():belongsTo
    {
        return $this->belongsTo(Sale::class);
    }


}
