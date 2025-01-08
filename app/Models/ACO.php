<?php

namespace App\Models;

use App\Enums\ACOEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string uuid
 * @property string code
 * @property string name
 * @property ACOEnum type
 * @property Carbon deleted_at
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class ACO extends Model implements Auditable
{
    use SoftDeletes, HasUuids;
    use \OwenIt\Auditing\Auditable;

    /**
     * @var string
     */
    protected $table = 'account_cos';


    /**
     * @var string
     */
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'type',
    ];
}
