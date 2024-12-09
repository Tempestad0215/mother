<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property int $client_id
 * @property float $amount
 * @property string $date
 * @property string $expire
 * @property float $balance
 * @property float $consumed
 * @property boolean $status
 * @property Date $deleted_at
 */

class Advance extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;


    /**
     * @var string[]
     */
    protected $fillable = [
        'client_id',
        'amount',
        'date',
        'expire',
        'balance',
        'consumed',
        'status'
    ];

}
