<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $client_id
 * @property float $limit_amount
 * @property int $limit_day
 * @property int $expired_day
 * @property float $balance
 * @property float $consumed
 * @property float $expired_amount
 * @property boolean $status
 * @property Date $deleted_at
 */
class Credit extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;


    /**
     * @var string[]
     */
    protected $fillable = [
        'client_id',
        'limit_amount',
        'limit_day',
        'expired_day',
        'balance',
        'consumed',
        'expired_amount',
        'status'
    ];


}
